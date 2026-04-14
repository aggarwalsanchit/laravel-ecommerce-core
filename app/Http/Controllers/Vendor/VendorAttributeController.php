<?php
// app/Http/Controllers/Vendor/VendorAttributeController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\AttributeRequest;
use App\Models\AttributeValueRequest;
use App\Models\Category;
use App\Models\AttributeGroup;
use App\Traits\LogsVendorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorAttributeController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new Middleware('permission:view_attributes', only: ['index', 'show']),
            // new Middleware('permission:request_attributes', only: ['createRequest', 'storeRequest', 'createValueRequest', 'storeValueRequest']),
        ];
    }

    /**
     * Display list of available attributes (read-only for vendors)
     */
    public function index(Request $request)
    {
        $query = Attribute::with('group', 'categories')
            ->where('status', true)
            ->where('approval_status', 'approved');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by group
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        // Filter by filterable/required
        if ($request->filled('is_filterable')) {
            $query->where('is_filterable', $request->is_filterable === 'true');
        }
        if ($request->filled('is_required')) {
            $query->where('is_required', $request->is_required === 'true');
        }

        $attributes = $query->orderBy('order')->paginate(20);

        // Get categories for filter
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        // Get attribute groups for filter
        $groups = AttributeGroup::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();

        // Get vendor's pending requests count
        $pendingRequestsCount = AttributeRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->count();

        $pendingValueRequestsCount = AttributeValueRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->count();

        return view('marketplace.pages.attributes.index', compact('attributes', 'categories', 'groups', 'pendingRequestsCount', 'pendingValueRequestsCount'));
    }

    /**
     * Show single attribute details
     */
    public function show($id)
    {
        $attribute = Attribute::with('group', 'categories', 'values')
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        // Get attribute values for select/multiselect types
        $values = [];
        if (in_array($attribute->type, ['select', 'multiselect', 'radio'])) {
            $values = $attribute->values()->where('status', true)->orderBy('order')->get();
        }

        return view('marketplace.pages.attributes.show', compact('attribute', 'values'));
    }

    /**
     * Show form to request a new attribute
     */
    public function createRequest()
    {
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        $groups = AttributeGroup::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();

        return view('marketplace.pages.attributes.request', compact('categories', 'groups'));
    }

    /**
     * Store a new attribute request
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attribute_requests,requested_name',
            'type' => 'required|in:text,textarea,number,decimal,select,multiselect,checkbox,radio,date,datetime,color,image,file,url,email,phone',
            'group_id' => 'nullable|exists:attribute_groups,id',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'reason' => 'required|string|max:500',
            'default_values' => 'nullable|array',
            'default_values.*' => 'nullable|string',
            'default_labels' => 'nullable|array',
            'default_colors' => 'nullable|array',
        ]);

        // Check if attribute already exists
        $existingAttribute = Attribute::where('name', $request->name)->first();
        if ($existingAttribute) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An attribute with this name already exists in our system.'
                ], 422);
            }
            return back()->with('error', 'An attribute with this name already exists in our system.');
        }

        // Prepare requested values for select/multiselect types
        $requestedValues = [];
        if (in_array($request->type, ['select', 'multiselect', 'radio']) && $request->has('default_values')) {
            foreach ($request->default_values as $index => $value) {
                if (!empty($value)) {
                    $requestedValues[] = [
                        'value' => $value,
                        'label' => $request->default_labels[$index] ?? null,
                        'color_code' => $request->default_colors[$index] ?? null,
                    ];
                }
            }
        }

        $attributeRequest = AttributeRequest::create([
            'vendor_id' => auth('vendor')->id(),
            'requested_name' => $request->name,
            'requested_slug' => Str::slug($request->name),
            'requested_type' => $request->type,
            'requested_group_id' => $request->group_id,
            'requested_category_ids' => $request->category_ids,
            'description' => $request->description,
            'reason' => $request->reason,
            'is_required' => $request->has('is_required'),
            'is_filterable' => $request->has('is_filterable'),
            'requested_values' => !empty($requestedValues) ? $requestedValues : null,
            'status' => 'pending',
        ]);

        // Prepare new values for logging
        $newValues = [
            'id' => $attributeRequest->id,
            'requested_name' => $attributeRequest->requested_name,
            'requested_type' => $attributeRequest->requested_type,
            'requested_group_id' => $attributeRequest->requested_group_id,
            'requested_category_ids' => $attributeRequest->requested_category_ids,
            'description' => $attributeRequest->description,
            'reason' => $attributeRequest->reason,
            'is_required' => $attributeRequest->is_required,
            'is_filterable' => $attributeRequest->is_filterable,
            'status' => $attributeRequest->status,
            'vendor_id' => $attributeRequest->vendor_id,
            'created_at' => $attributeRequest->created_at ? $attributeRequest->created_at->toDateTimeString() : null,
        ];

        // Get category names for better logging
        $categoryNames = Category::whereIn('id', $request->category_ids)->pluck('name')->implode(', ');

        // Log activity
        $this->logActivity(
            'request_attribute',                         // action
            'attribute_request',                         // entity_type
            $attributeRequest->id,                       // entity_id
            $attributeRequest->requested_name,           // entity_name
            null,                                        // old_values
            $newValues,                                  // new_values
            "Requested new attribute: {$attributeRequest->requested_name} (Type: {$attributeRequest->requested_type}) - Categories: {$categoryNames} - Reason: " . Str::limit($request->reason, 100)
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute request submitted successfully. Admin will review it.',
                'request' => $attributeRequest
            ]);
        }

        return redirect()->route('vendor.attributes.requests.index')
            ->with('success', 'Attribute request submitted successfully. Admin will review it.');
    }

    /**
     * Show form to request a new attribute value
     */
    public function createValueRequest()
    {
        $attributes = Attribute::where('status', true)
            ->where('approval_status', 'approved')
            ->whereIn('type', ['select', 'multiselect', 'radio', 'color'])
            ->orderBy('name')
            ->get();

        return view('marketplace.pages.attributes.value-request', compact('attributes'));
    }

    /**
     * Store a new attribute value request
     */
    public function storeValueRequest(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'reason' => 'required|string|max:500',
        ]);

        $attribute = Attribute::find($request->attribute_id);

        // Check if value already exists
        $existingValue = AttributeValue::where('attribute_id', $attribute->id)
            ->where('value', $request->value)
            ->first();

        if ($existingValue) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This value already exists for this attribute.'
                ], 422);
            }
            return back()->with('error', 'This value already exists for this attribute.');
        }

        // Check if pending request already exists
        $existingRequest = AttributeValueRequest::where('vendor_id', auth('vendor')->id())
            ->where('attribute_id', $request->attribute_id)
            ->where('requested_value', $request->value)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this value.'
                ], 422);
            }
            return back()->with('error', 'You already have a pending request for this value.');
        }

        $valueRequest = AttributeValueRequest::create([
            'vendor_id' => auth('vendor')->id(),
            'attribute_id' => $request->attribute_id,
            'requested_value' => $request->value,
            'requested_label' => $request->label,
            'requested_color_code' => $request->color_code,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Prepare new values for logging
        $newValues = [
            'id' => $valueRequest->id,
            'attribute_id' => $valueRequest->attribute_id,
            'attribute_name' => $attribute->name,
            'requested_value' => $valueRequest->requested_value,
            'requested_label' => $valueRequest->requested_label,
            'requested_color_code' => $valueRequest->requested_color_code,
            'reason' => $valueRequest->reason,
            'status' => $valueRequest->status,
            'vendor_id' => $valueRequest->vendor_id,
            'created_at' => $valueRequest->created_at ? $valueRequest->created_at->toDateTimeString() : null,
        ];

        // Log activity
        $this->logActivity(
            'request_attribute_value',                   // action
            'attribute_value_request',                   // entity_type
            $valueRequest->id,                           // entity_id
            $valueRequest->requested_value,              // entity_name
            null,                                        // old_values
            $newValues,                                  // new_values
            "Requested new value '{$valueRequest->requested_value}' for attribute '{$attribute->name}' - Reason: " . Str::limit($request->reason, 100)
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Value request submitted successfully. Admin will review it.',
                'request' => $valueRequest
            ]);
        }

        return redirect()->route('vendor.attributes.value-requests.index')
            ->with('success', 'Value request submitted successfully. Admin will review it.');
    }

    /**
     * Display vendor's attribute requests
     */
    public function myRequests(Request $request)
    {
        $query = AttributeRequest::where('vendor_id', auth('vendor')->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('requested_type', $request->type);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => AttributeRequest::where('vendor_id', auth('vendor')->id())->count(),
            'pending' => AttributeRequest::where('vendor_id', auth('vendor')->id())->where('status', 'pending')->count(),
            'approved' => AttributeRequest::where('vendor_id', auth('vendor')->id())->where('status', 'approved')->count(),
            'rejected' => AttributeRequest::where('vendor_id', auth('vendor')->id())->where('status', 'rejected')->count(),
        ];

        return view('marketplace.pages.attributes.my-requests', compact('requests', 'statistics'));
    }

    /**
     * Show single attribute request details
     */
    public function showRequest($id)
    {
        $request = AttributeRequest::where('vendor_id', auth('vendor')->id())
            ->with('vendor', 'approvedBy', 'createdAttribute', 'requestedGroup')
            ->findOrFail($id);

        return view('marketplace.pages.attributes.request-details', compact('request'));
    }

    /**
     * Cancel a pending attribute request
     */
    public function cancelRequest($id)
    {
        $attributeRequest = AttributeRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $oldValues = $attributeRequest->toArray();
        $requestName = $attributeRequest->requested_name;
        $attributeRequest->delete();

        $this->logActivity(
            'cancel_attribute_request',                  // action
            'attribute_request',                         // entity_type
            $id,                                         // entity_id
            $requestName,                                // entity_name
            $oldValues,                                  // old_values
            null,                                        // new_values
            "Cancelled attribute request: {$requestName}"
        );

        return redirect()->route('vendor.attributes.requests.index')
            ->with('success', 'Attribute request cancelled successfully.');
    }

    /**
     * Display vendor's attribute value requests
     */
    public function myValueRequests(Request $request)
    {
        $query = AttributeValueRequest::where('vendor_id', auth('vendor')->id())->with('attribute');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by attribute
        if ($request->filled('attribute_id')) {
            $query->where('attribute_id', $request->attribute_id);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => AttributeValueRequest::where('vendor_id', auth('vendor')->id())->count(),
            'pending' => AttributeValueRequest::where('vendor_id', auth('vendor')->id())->where('status', 'pending')->count(),
            'approved' => AttributeValueRequest::where('vendor_id', auth('vendor')->id())->where('status', 'approved')->count(),
            'rejected' => AttributeValueRequest::where('vendor_id', auth('vendor')->id())->where('status', 'rejected')->count(),
        ];

        $attributes = Attribute::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        return view('marketplace.pages.attributes.my-value-requests', compact('requests', 'statistics', 'attributes'));
    }

    /**
     * Show single attribute value request details
     */
    public function showValueRequest($id)
    {
        $request = AttributeValueRequest::where('vendor_id', auth('vendor')->id())
            ->with('vendor', 'attribute', 'approvedBy', 'createdValue')
            ->findOrFail($id);

        return view('marketplace.pages.attributes.value-request-details', compact('request'));
    }

    /**
     * Cancel a pending attribute value request
     */
    public function cancelValueRequest($id)
    {
        $valueRequest = AttributeValueRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $oldValues = $valueRequest->toArray();
        $requestValue = $valueRequest->requested_value;
        $valueRequest->delete();

        $this->logActivity(
            'cancel_attribute_value_request',            // action
            'attribute_value_request',                   // entity_type
            $id,                                         // entity_id
            $requestValue,                               // entity_name
            $oldValues,                                  // old_values
            null,                                        // new_values
            "Cancelled attribute value request: {$requestValue}"
        );

        return redirect()->route('vendor.attributes.value-requests.index')
            ->with('success', 'Value request cancelled successfully.');
    }

    /**
     * Get attributes by category for AJAX request
     */
    public function getAttributesByCategory($categoryId)
    {
        $attributes = Attribute::whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->with('values')
            ->orderBy('order')
            ->get();

        return response()->json([
            'attributes' => $attributes
        ]);
    }

    /**
     * Get attribute values for AJAX request
     */
    public function getAttributeValues($attributeId)
    {
        $attribute = Attribute::findOrFail($attributeId);
        
        $values = [];
        if (in_array($attribute->type, ['select', 'multiselect', 'radio', 'color'])) {
            $values = $attribute->values()->where('status', true)->orderBy('order')->get();
        }

        return response()->json([
            'attribute' => $attribute,
            'values' => $values
        ]);
    }
}
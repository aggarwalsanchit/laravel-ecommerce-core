<?php
// app/Http/Controllers/Admin/AttributeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AttributeValue;
use App\Models\AttributeRequest;
use App\Models\AttributeValueRequest;
use App\Models\AttributeAnalytic;
use App\Models\Category;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class AttributeController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_attributes', only: ['index', 'show', 'analytics', 'manageValues', 'pendingRequests', 'valueRequests']),
            new Middleware('permission:create_attributes', only: ['create', 'store', 'storeValue']),
            new Middleware('permission:edit_attributes', only: ['edit', 'update', 'updateValue', 'toggleStatus', 'toggleFeatured', 'toggleFilterable', 'reorderValues', 'bulkAction']),
            new Middleware('permission:approve_attributes', only: ['approveRequest', 'rejectRequest', 'approveValueRequest', 'rejectValueRequest']),
            new Middleware('permission:delete_attributes', only: ['destroy', 'destroyValue', 'deleteRequest', 'deleteValueRequest']),
        ];
    }

    /**
     * Display a listing of attributes
     */
    public function index(Request $request)
    {
        $query = Attribute::with('group', 'categories');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        if ($request->filled('is_filterable')) {
            $query->where('is_filterable', $request->is_filterable === 'true');
        }

        if ($request->filled('is_required')) {
            $query->where('is_required', $request->is_required === 'true');
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $attributes = $query->paginate(15);

        // Get analytics for each attribute
        foreach ($attributes as $attribute) {
            $analytics = AttributeAnalytic::where('attribute_id', $attribute->id)
                ->where('date', today()->toDateString())
                ->first();
            $attribute->usage_count = $analytics->usage_count ?? 0;
            $attribute->view_count = $analytics->view_count ?? 0;
        }

        $statistics = [
            'total' => Attribute::count(),
            'active' => Attribute::where('status', true)->count(),
            'filterable' => Attribute::where('is_filterable', true)->count(),
            'required' => Attribute::where('is_required', true)->count(),
            'pending' => Attribute::where('approval_status', 'pending')->count(),
            'rejected' => Attribute::where('approval_status', 'rejected')->count(),
        ];

        $groups = AttributeGroup::active()->orderBy('order')->get();
        $categories = Category::active()->orderBy('name')->get();

        if ($request->ajax()) {
            $table = view('admin.pages.attributes.partials.attributes-table', compact('attributes'))->render();
            $pagination = $attributes->appends($request->query())->links('pagination::bootstrap-5')->render();
            return response()->json(['table' => $table, 'pagination' => $pagination, 'statistics' => $statistics]);
        }

        return view('admin.pages.attributes.index', compact('attributes', 'statistics', 'groups', 'categories'));
    }

    /**
     * Show form for creating new attribute
     */
    public function create()
    {
        $groups = AttributeGroup::active()->orderBy('order')->get();
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.pages.attributes.create', compact('groups', 'categories'));
    }

    /**
     * Store newly created attribute
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'type' => 'required|in:text,textarea,number,decimal,select,multiselect,checkbox,radio,date,datetime,color,image,file,url,email,phone',
            'group_id' => 'nullable|exists:attribute_groups,id',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_searchable' => 'boolean',
            'is_comparable' => 'boolean',
            'show_on_product_page' => 'boolean',
            'unit' => 'nullable|string|max:20',
            'min_value' => 'nullable|string',
            'max_value' => 'nullable|string',
            'max_length' => 'nullable|integer',
            'default_value' => 'nullable|string',
            'placeholder' => 'nullable|string',
            'help_text' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['category_ids']);
        $data['slug'] = Str::slug($request->name);
        $data['approval_status'] = 'approved';

        $attribute = Attribute::create($data);

        if ($request->has('category_ids')) {
            $attribute->categories()->attach($request->category_ids);
        }

        // Create default values for select/multiselect if provided
        if (in_array($request->type, ['select', 'multiselect', 'radio']) && $request->has('default_values')) {
            foreach ($request->default_values as $index => $value) {
                if (!empty($value)) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                        'label' => $request->default_labels[$index] ?? $value,
                        'order' => $index,
                    ]);
                }
            }
        }

        $this->logActivity('create', 'attribute', 'admin', $attribute->id, $attribute->name, null, $attribute->toArray(), "Created new attribute: {$attribute->name}");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Attribute created successfully.', 'attribute' => $attribute]);
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    /**
     * Display attribute details
     */
    public function show(Attribute $attribute)
    {
        $attribute->load('group', 'categories', 'values');
        $recentAnalytics = AttributeAnalytic::where('attribute_id', $attribute->id)
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();
        return view('admin.pages.attributes.show', compact('attribute', 'recentAnalytics'));
    }

    /**
     * Show form for editing attribute
     */
    public function edit(Attribute $attribute)
    {
        $groups = AttributeGroup::active()->orderBy('order')->get();
        $categories = Category::active()->orderBy('name')->get();
        $selectedCategories = $attribute->categories->pluck('id')->toArray();
        return view('admin.pages.attributes.edit', compact('attribute', 'groups', 'categories', 'selectedCategories'));
    }

    /**
     * Update attribute
     */
    public function update(Request $request, Attribute $attribute)
    {
        $oldData = $attribute->toArray();

        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'type' => 'required|in:text,textarea,number,decimal,select,multiselect,checkbox,radio,date,datetime,color,image,file,url,email,phone',
            'group_id' => 'nullable|exists:attribute_groups,id',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_searchable' => 'boolean',
            'is_comparable' => 'boolean',
            'show_on_product_page' => 'boolean',
            'unit' => 'nullable|string|max:20',
            'min_value' => 'nullable|string',
            'max_value' => 'nullable|string',
            'max_length' => 'nullable|integer',
            'default_value' => 'nullable|string',
            'placeholder' => 'nullable|string',
            'help_text' => 'nullable|string',
            'icon' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $attribute->update($request->except(['category_ids']));

        // Sync categories
        if ($request->has('category_ids')) {
            $attribute->categories()->sync($request->category_ids);
        } else {
            $attribute->categories()->detach();
        }

        $this->logActivity('update', 'attribute', 'admin', $attribute->id, $attribute->name, $oldData, $attribute->toArray(), "Updated attribute: {$attribute->name}");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Attribute updated successfully.']);
        }

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    /**
     * Delete attribute
     */
    public function destroy(Attribute $attribute)
    {
        if ($attribute->productValues()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete attribute because it has ' . $attribute->productValues()->count() . ' product values.'
            ], 422);
        }

        $attributeName = $attribute->name;
        $attribute->delete();

        $this->logActivity('delete', 'attribute', 'admin', $attribute->id, $attributeName, null, null, "Deleted attribute: {$attributeName}");

        return response()->json(['success' => true, 'message' => 'Attribute deleted successfully.']);
    }

    /**
     * Manage attribute values (for select/multiselect types)
     */
    public function manageValues(Attribute $attribute)
    {
        $values = $attribute->values()->orderBy('order')->get();
        return view('admin.pages.attributes.values', compact('attribute', 'values'));
    }

    /**
     * Store attribute value
     */
    public function storeValue(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'image' => 'nullable|image|max:1024',
            'price_adjustment' => 'nullable|numeric',
            'weight_adjustment' => 'nullable|numeric',
            'is_default' => 'boolean',
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->value) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('attribute-values', $filename, 'public');
            $data['image'] = $filename;
        }

        $data['order'] = $attribute->values()->max('order') + 1;
        $value = $attribute->values()->create($data);

        return response()->json(['success' => true, 'message' => 'Value added successfully.', 'value' => $value]);
    }

    /**
     * Update attribute value
     */
    public function updateValue(Request $request, AttributeValue $value)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'image' => 'nullable|image|max:1024',
            'price_adjustment' => 'nullable|numeric',
            'weight_adjustment' => 'nullable|numeric',
            'is_default' => 'boolean',
            'status' => 'boolean',
        ]);

        $data = $request->except(['image', 'remove_image']);

        if ($request->hasFile('image')) {
            if ($value->image) {
                Storage::disk('public')->delete('attribute-values/' . $value->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->value) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('attribute-values', $filename, 'public');
            $data['image'] = $filename;
        }

        if ($request->has('remove_image') && $request->remove_image) {
            if ($value->image) {
                Storage::disk('public')->delete('attribute-values/' . $value->image);
            }
            $data['image'] = null;
        }

        $value->update($data);

        return response()->json(['success' => true, 'message' => 'Value updated successfully.']);
    }

    /**
     * Delete attribute value
     */
    public function destroyValue(AttributeValue $value)
    {
        if ($value->productValues()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete value because it is used in ' . $value->productValues()->count() . ' products.'
            ], 422);
        }

        if ($value->image) {
            Storage::disk('public')->delete('attribute-values/' . $value->image);
        }

        $value->delete();

        return response()->json(['success' => true, 'message' => 'Value deleted successfully.']);
    }

    /**
     * Reorder attribute values
     */
    public function reorderValues(Request $request)
    {
        $request->validate([
            'values' => 'required|array',
            'values.*.id' => 'required|exists:attribute_values,id',
            'values.*.order' => 'required|integer',
        ]);

        foreach ($request->values as $valueData) {
            AttributeValue::where('id', $valueData['id'])->update(['order' => $valueData['order']]);
        }

        return response()->json(['success' => true, 'message' => 'Values reordered successfully.']);
    }

    /**
     * Toggle attribute status
     */
    public function toggleStatus(Attribute $attribute)
    {
        $oldStatus = $attribute->status;
        $attribute->update(['status' => !$attribute->status]);

        $this->logActivity('toggle_status', 'attribute', 'admin', $attribute->id, $attribute->name, ['status' => $oldStatus], ['status' => $attribute->status], "Toggled attribute status for '{$attribute->name}'");

        return response()->json(['success' => true, 'message' => 'Status updated.', 'status' => $attribute->status]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Attribute $attribute)
    {
        $attribute->update(['is_featured' => !$attribute->is_featured]);
        return response()->json(['success' => true, 'message' => 'Featured status updated.', 'is_featured' => $attribute->is_featured]);
    }

    /**
     * Toggle filterable status
     */
    public function toggleFilterable(Attribute $attribute)
    {
        $attribute->update(['is_filterable' => !$attribute->is_filterable]);
        return response()->json(['success' => true, 'message' => 'Filterable status updated.', 'is_filterable' => $attribute->is_filterable]);
    }

    /**
     * Get attributes by category (for AJAX)
     */
    /**
     * Get attributes by multiple category IDs
     */
    public function getByCategories(Request $request)
    {
        $categoryIds = $request->category_ids;

        if (empty($categoryIds)) {
            return response()->json([
                'success' => true,
                'attributes' => []
            ]);
        }

        $attributes = Attribute::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('category_id', $categoryIds);
        })
            ->with('values')
            ->where('status', true)
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'attributes' => $attributes
        ]);
    }

    /**
     * Get attributes for a single category (legacy/other uses)
     */
    public function getByCategory($categoryId)
    {
        $attributes = Attribute::whereHas('categories', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        })->with('values')->orderBy('order')->get();

        return response()->json(['attributes' => $attributes]);
    }

    /**
     * Bulk action on attributes
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,filterable,unfilterable,approve,reject',
            'attribute_ids' => 'required|string',
        ]);

        $action = $request->action;
        $attributeIds = json_decode($request->attribute_ids);
        $attributes = Attribute::whereIn('id', $attributeIds)->get();
        $count = 0;
        $errors = [];

        foreach ($attributes as $attribute) {
            try {
                switch ($action) {
                    case 'activate':
                        $attribute->update(['status' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $attribute->update(['status' => false]);
                        $count++;
                        break;
                    case 'feature':
                        $attribute->update(['is_featured' => true]);
                        $count++;
                        break;
                    case 'unfeature':
                        $attribute->update(['is_featured' => false]);
                        $count++;
                        break;
                    case 'filterable':
                        $attribute->update(['is_filterable' => true]);
                        $count++;
                        break;
                    case 'unfilterable':
                        $attribute->update(['is_filterable' => false]);
                        $count++;
                        break;
                    case 'approve':
                        if ($attribute->approval_status === 'pending') {
                            $attribute->update(['approval_status' => 'approved', 'approved_by' => auth('admin')->id(), 'approved_at' => now(), 'status' => true]);
                            $count++;
                        }
                        break;
                    case 'reject':
                        if ($attribute->approval_status === 'pending') {
                            $attribute->update(['approval_status' => 'rejected', 'approved_by' => auth('admin')->id(), 'approved_at' => now(), 'status' => false]);
                            $count++;
                        }
                        break;
                    case 'delete':
                        if ($attribute->productValues()->count() > 0) {
                            $errors[] = "Cannot delete '{$attribute->name}' - has product values.";
                            continue 2;
                        }
                        $attribute->delete();
                        $count++;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$attribute->name}': " . $e->getMessage();
            }
        }

        $message = "{$count} attributes processed successfully." . (!empty($errors) ? " Errors: " . implode(' ', $errors) : "");
        return response()->json(['success' => true, 'message' => $message, 'count' => $count, 'errors' => $errors]);
    }

    /**
     * Analytics dashboard
     */
    public function analytics()
    {
        $totalAttributes = Attribute::count();
        $activeAttributes = Attribute::where('status', true)->count();
        $filterableAttributes = Attribute::where('is_filterable', true)->count();
        $requiredAttributes = Attribute::where('is_required', true)->count();

        $topUsedAttributes = Attribute::withSum('analytics', 'usage_count')
            ->orderBy('analytics_sum_usage_count', 'desc')
            ->take(10)
            ->get();

        $topViewedAttributes = Attribute::withSum('analytics', 'view_count')
            ->orderBy('analytics_sum_view_count', 'desc')
            ->take(10)
            ->get();

        $attributesByType = Attribute::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        return view('admin.pages.attributes.analytics', compact(
            'totalAttributes',
            'activeAttributes',
            'filterableAttributes',
            'requiredAttributes',
            'topUsedAttributes',
            'topViewedAttributes',
            'attributesByType'
        ));
    }

    /**
     * Display pending attribute requests from vendors
     */
    public function pendingRequests(Request $request)
    {
        $query = AttributeRequest::with('vendor');

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('requested_type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('requested_name', 'like', "%{$search}%")
                    ->orWhere('requested_slug', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total_pending' => AttributeRequest::where('status', 'pending')->count(),
            'total_approved' => AttributeRequest::where('status', 'approved')->count(),
            'total_rejected' => AttributeRequest::where('status', 'rejected')->count(),
            'total_requests' => AttributeRequest::count(),
        ];

        return view('admin.pages.attributes.requests', compact('requests', 'statistics', 'status'));
    }

    /**
     * View single attribute request details
     */
    public function viewRequest($id)
    {
        $attributeRequest = AttributeRequest::with('vendor', 'approvedBy', 'createdAttribute', 'requestedGroup')->findOrFail($id);
        return view('admin.pages.attributes.request-details', compact('attributeRequest'));
    }

    /**
     * Approve an attribute request and create the attribute
     */
    public function approveRequest(Request $request, $id)
    {
        $attributeRequest = AttributeRequest::with('vendor')->findOrFail($id);

        if ($attributeRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        // Check if attribute already exists
        $existingAttribute = Attribute::where('name', $attributeRequest->requested_name)->first();
        if ($existingAttribute) {
            return response()->json([
                'success' => false,
                'message' => 'An attribute with name "' . $attributeRequest->requested_name . '" already exists. Please reject this request.'
            ], 422);
        }

        // Create the attribute
        $attribute = Attribute::create([
            'name' => $attributeRequest->requested_name,
            'slug' => $attributeRequest->requested_slug ?? Str::slug($attributeRequest->requested_name),
            'type' => $attributeRequest->requested_type,
            'description' => $attributeRequest->description,
            'group_id' => $attributeRequest->requested_group_id,
            'is_required' => $attributeRequest->is_required,
            'is_filterable' => $attributeRequest->is_filterable,
            'requested_by' => $attributeRequest->vendor_id,
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
            'order' => Attribute::max('order') + 1,
        ]);

        // Attach to categories
        if ($attributeRequest->requested_category_ids) {
            $attribute->categories()->attach($attributeRequest->requested_category_ids);
        }

        // Create values if provided (for select/multiselect/radio)
        if ($attributeRequest->requested_values && in_array($attribute->type, ['select', 'multiselect', 'radio'])) {
            foreach ($attributeRequest->requested_values as $index => $valueData) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value' => $valueData['value'] ?? $valueData,
                    'label' => $valueData['label'] ?? null,
                    'color_code' => $valueData['color_code'] ?? null,
                    'price_adjustment' => $valueData['price_adjustment'] ?? 0,
                    'weight_adjustment' => $valueData['weight_adjustment'] ?? 0,
                    'order' => $index,
                    'is_default' => isset($valueData['is_default']) && $valueData['is_default'],
                    'status' => true,
                ]);
            }
        }

        // Update the request
        $attributeRequest->update([
            'status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'created_attribute_id' => $attribute->id,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'approve_attribute_request',
            'attribute_request',
            'admin',
            $attributeRequest->id,
            $attributeRequest->requested_name,
            null,
            [
                'request_id' => $attributeRequest->id,
                'attribute_id' => $attribute->id,
                'vendor_id' => $attributeRequest->vendor_id,
            ],
            "Approved attribute request '{$attributeRequest->requested_name}' from Vendor #{$attributeRequest->vendor_id}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute request approved and attribute created successfully.',
            'attribute' => $attribute
        ]);
    }

    /**
     * Reject an attribute request
     */
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $attributeRequest = AttributeRequest::findOrFail($id);

        if ($attributeRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $attributeRequest->update([
            'status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'reject_attribute_request',
            'attribute_request',
            'admin',
            $attributeRequest->id,
            $attributeRequest->requested_name,
            null,
            [
                'request_id' => $attributeRequest->id,
                'vendor_id' => $attributeRequest->vendor_id,
                'rejection_reason' => $request->rejection_reason,
            ],
            "Rejected attribute request '{$attributeRequest->requested_name}' - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute request rejected successfully.'
        ]);
    }

    /**
     * Delete an attribute request
     */
    public function deleteRequest($id)
    {
        $attributeRequest = AttributeRequest::findOrFail($id);

        if ($attributeRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved requests. Delete the attribute instead.'
            ], 422);
        }

        $requestName = $attributeRequest->requested_name;
        $attributeRequest->delete();

        $this->logActivity(
            'delete_attribute_request',
            'attribute_request',
            'admin',
            $id,
            $requestName,
            null,
            null,
            "Deleted attribute request '{$requestName}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Attribute request deleted successfully.'
        ]);
    }

    /**
     * Bulk action on attribute requests
     */
    public function bulkRequestAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'request_ids' => 'required|string',
        ]);

        $action = $request->action;
        $requestIds = json_decode($request->request_ids);
        $requests = AttributeRequest::whereIn('id', $requestIds)->get();

        $count = 0;
        $errors = [];
        $processedRequests = [];

        foreach ($requests as $attributeRequest) {
            try {
                if ($attributeRequest->status !== 'pending' && in_array($action, ['approve', 'reject'])) {
                    $errors[] = "Request '{$attributeRequest->requested_name}' is already processed.";
                    continue;
                }

                switch ($action) {
                    case 'approve':
                        $existingAttribute = Attribute::where('name', $attributeRequest->requested_name)->first();
                        if ($existingAttribute) {
                            $errors[] = "Attribute '{$attributeRequest->requested_name}' already exists.";
                            continue 2;
                        }

                        $attribute = Attribute::create([
                            'name' => $attributeRequest->requested_name,
                            'slug' => $attributeRequest->requested_slug ?? Str::slug($attributeRequest->requested_name),
                            'type' => $attributeRequest->requested_type,
                            'description' => $attributeRequest->description,
                            'group_id' => $attributeRequest->requested_group_id,
                            'is_required' => $attributeRequest->is_required,
                            'is_filterable' => $attributeRequest->is_filterable,
                            'requested_by' => $attributeRequest->vendor_id,
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'approval_status' => 'approved',
                            'status' => true,
                            'order' => Attribute::max('order') + 1,
                        ]);

                        if ($attributeRequest->requested_category_ids) {
                            $attribute->categories()->attach($attributeRequest->requested_category_ids);
                        }

                        if ($attributeRequest->requested_values && in_array($attribute->type, ['select', 'multiselect', 'radio'])) {
                            foreach ($attributeRequest->requested_values as $index => $valueData) {
                                AttributeValue::create([
                                    'attribute_id' => $attribute->id,
                                    'value' => $valueData['value'] ?? $valueData,
                                    'label' => $valueData['label'] ?? null,
                                    'color_code' => $valueData['color_code'] ?? null,
                                    'price_adjustment' => $valueData['price_adjustment'] ?? 0,
                                    'weight_adjustment' => $valueData['weight_adjustment'] ?? 0,
                                    'order' => $index,
                                    'status' => true,
                                ]);
                            }
                        }

                        $attributeRequest->update([
                            'status' => 'approved',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'created_attribute_id' => $attribute->id,
                        ]);
                        $count++;
                        $processedRequests[] = $attributeRequest->requested_name;
                        break;

                    case 'reject':
                        $attributeRequest->update([
                            'status' => 'rejected',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'rejection_reason' => $request->rejection_reason ?? 'Bulk rejection',
                        ]);
                        $count++;
                        $processedRequests[] = $attributeRequest->requested_name;
                        break;

                    case 'delete':
                        if ($attributeRequest->status === 'approved') {
                            $errors[] = "Cannot delete approved request '{$attributeRequest->requested_name}'.";
                            continue 2;
                        }
                        $attributeRequest->delete();
                        $count++;
                        $processedRequests[] = $attributeRequest->requested_name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$attributeRequest->requested_name}': " . $e->getMessage();
            }
        }

        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action . '_requests',
                'attribute_request',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_requests' => $processedRequests,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} attribute requests"
            );
        }

        $message = "{$count} requests processed successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(' ', $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count,
            'errors' => $errors
        ]);
    }

    /**
     * Display pending attribute value requests from vendors
     */
    public function valueRequests(Request $request)
    {
        $query = AttributeValueRequest::with('vendor', 'attribute');

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by attribute
        if ($request->filled('attribute_id')) {
            $query->where('attribute_id', $request->attribute_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('requested_value', 'like', "%{$search}%")
                    ->orWhere('requested_label', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total_pending' => AttributeValueRequest::where('status', 'pending')->count(),
            'total_approved' => AttributeValueRequest::where('status', 'approved')->count(),
            'total_rejected' => AttributeValueRequest::where('status', 'rejected')->count(),
            'total_requests' => AttributeValueRequest::count(),
        ];

        $attributes = Attribute::active()->orderBy('name')->get();

        return view('admin.pages.attributes.value-requests', compact('requests', 'statistics', 'status', 'attributes'));
    }

    /**
     * View single attribute value request details
     */
    public function viewValueRequest($id)
    {
        $valueRequest = AttributeValueRequest::with('vendor', 'attribute', 'approvedBy', 'createdValue')->findOrFail($id);
        return view('admin.pages.attributes.value-request-details', compact('valueRequest'));
    }

    /**
     * Approve an attribute value request and create the value
     */
    public function approveValueRequest(Request $request, $id)
    {
        $valueRequest = AttributeValueRequest::with('vendor', 'attribute')->findOrFail($id);

        if ($valueRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $attribute = $valueRequest->attribute;

        // Check if value already exists
        $existingValue = AttributeValue::where('attribute_id', $attribute->id)
            ->where('value', $valueRequest->requested_value)
            ->first();

        if ($existingValue) {
            return response()->json([
                'success' => false,
                'message' => 'A value "' . $valueRequest->requested_value . '" already exists for this attribute.'
            ], 422);
        }

        // Create the attribute value
        $attributeValue = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value' => $valueRequest->requested_value,
            'label' => $valueRequest->requested_label,
            'color_code' => $valueRequest->requested_color_code,
            'image' => $valueRequest->requested_image,
            'order' => $attribute->values()->max('order') + 1,
            'status' => true,
        ]);

        // Update the request
        $valueRequest->update([
            'status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'created_value_id' => $attributeValue->id,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'approve_value_request',
            'attribute_value_request',
            'admin',
            $valueRequest->id,
            $valueRequest->requested_value,
            null,
            [
                'request_id' => $valueRequest->id,
                'attribute_id' => $attribute->id,
                'value_id' => $attributeValue->id,
                'vendor_id' => $valueRequest->vendor_id,
            ],
            "Approved value request '{$valueRequest->requested_value}' for attribute '{$attribute->name}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Value request approved and value created successfully.',
            'value' => $attributeValue
        ]);
    }

    /**
     * Reject an attribute value request
     */
    public function rejectValueRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $valueRequest = AttributeValueRequest::findOrFail($id);

        if ($valueRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $attributeName = $valueRequest->attribute->name ?? 'Unknown';

        $valueRequest->update([
            'status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'reject_value_request',
            'attribute_value_request',
            'admin',
            $valueRequest->id,
            $valueRequest->requested_value,
            null,
            [
                'request_id' => $valueRequest->id,
                'vendor_id' => $valueRequest->vendor_id,
                'attribute_name' => $attributeName,
                'rejection_reason' => $request->rejection_reason,
            ],
            "Rejected value request '{$valueRequest->requested_value}' for attribute '{$attributeName}' - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Value request rejected successfully.'
        ]);
    }

    /**
     * Delete an attribute value request
     */
    public function deleteValueRequest($id)
    {
        $valueRequest = AttributeValueRequest::findOrFail($id);

        if ($valueRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved requests. Delete the value instead.'
            ], 422);
        }

        $requestValue = $valueRequest->requested_value;
        $valueRequest->delete();

        $this->logActivity(
            'delete_value_request',
            'attribute_value_request',
            'admin',
            $id,
            $requestValue,
            null,
            null,
            "Deleted value request '{$requestValue}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Value request deleted successfully.'
        ]);
    }

    /**
     * Bulk action on value requests
     */
    public function bulkValueRequestAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'request_ids' => 'required|string',
        ]);

        $action = $request->action;
        $requestIds = json_decode($request->request_ids);
        $requests = AttributeValueRequest::whereIn('id', $requestIds)->get();

        $count = 0;
        $errors = [];
        $processedRequests = [];

        foreach ($requests as $valueRequest) {
            try {
                if ($valueRequest->status !== 'pending' && in_array($action, ['approve', 'reject'])) {
                    $errors[] = "Request '{$valueRequest->requested_value}' is already processed.";
                    continue;
                }

                $attribute = $valueRequest->attribute;

                switch ($action) {
                    case 'approve':
                        $existingValue = AttributeValue::where('attribute_id', $attribute->id)
                            ->where('value', $valueRequest->requested_value)
                            ->first();

                        if ($existingValue) {
                            $errors[] = "Value '{$valueRequest->requested_value}' already exists for attribute '{$attribute->name}'.";
                            continue 2;
                        }

                        $attributeValue = AttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $valueRequest->requested_value,
                            'label' => $valueRequest->requested_label,
                            'color_code' => $valueRequest->requested_color_code,
                            'image' => $valueRequest->requested_image,
                            'order' => $attribute->values()->max('order') + 1,
                            'status' => true,
                        ]);

                        $valueRequest->update([
                            'status' => 'approved',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'created_value_id' => $attributeValue->id,
                        ]);
                        $count++;
                        $processedRequests[] = $valueRequest->requested_value;
                        break;

                    case 'reject':
                        $valueRequest->update([
                            'status' => 'rejected',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'rejection_reason' => $request->rejection_reason ?? 'Bulk rejection',
                        ]);
                        $count++;
                        $processedRequests[] = $valueRequest->requested_value;
                        break;

                    case 'delete':
                        if ($valueRequest->status === 'approved') {
                            $errors[] = "Cannot delete approved request '{$valueRequest->requested_value}'.";
                            continue 2;
                        }
                        $valueRequest->delete();
                        $count++;
                        $processedRequests[] = $valueRequest->requested_value;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$valueRequest->requested_value}': " . $e->getMessage();
            }
        }

        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action . '_value_requests',
                'attribute_value_request',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_requests' => $processedRequests,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} attribute value requests"
            );
        }

        $message = "{$count} requests processed successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(' ', $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count,
            'errors' => $errors
        ]);
    }
}

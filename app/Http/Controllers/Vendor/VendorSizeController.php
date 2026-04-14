<?php
// app/Http/Controllers/Vendor/VendorSizeController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\SizeRequest;
use App\Models\Category;
use App\Traits\LogsVendorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorSizeController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new Middleware('permission:view_sizes', only: ['index', 'show']),
            // new Middleware('permission:request_sizes', only: ['createRequest', 'storeRequest']),
        ];
    }

    /**
     * Display list of available sizes (read-only for vendors)
     */
    public function index(Request $request)
    {
        $query = Size::where('status', true)
            ->where('approval_status', 'approved');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('us_size', 'like', "%{$search}%")
                    ->orWhere('eu_size', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by featured/popular
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'true');
        }
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'true');
        }

        $sizes = $query->orderBy('order')->paginate(24);

        // Get categories for filter
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        // Get vendor's pending requests count
        $pendingRequestsCount = SizeRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->count();

        return view('marketplace.pages.sizes.index', compact('sizes', 'categories', 'pendingRequestsCount'));
    }

    /**
     * Show single size details
     */
    public function show($id)
    {
        $size = Size::with('categories')
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        return view('marketplace.pages.sizes.show', compact('size'));
    }

    /**
     * Show form to request a new size
     */
    public function createRequest()
    {
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        return view('marketplace.pages.sizes.request', compact('categories'));
    }

    /**
     * Store a new size request
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:size_requests,requested_name',
            'code' => 'required|string|max:20|unique:size_requests,requested_code',
            'gender' => 'required|in:Men,Women,Unisex,Kids',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hip' => 'nullable|numeric',
            'inseam' => 'nullable|numeric',
            'shoulder' => 'nullable|numeric',
            'sleeve' => 'nullable|numeric',
            'neck' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'us_size' => 'nullable|string|max:20',
            'uk_size' => 'nullable|string|max:20',
            'eu_size' => 'nullable|string|max:20',
            'au_size' => 'nullable|string|max:20',
            'jp_size' => 'nullable|string|max:20',
            'cn_size' => 'nullable|string|max:20',
            'int_size' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'reason' => 'required|string|max:500',
        ]);

        // Check if size already exists
        $existingSize = Size::where('code', $request->code)->first();
        if ($existingSize) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A size with this code already exists in our system.'
                ], 422);
            }
            return back()->with('error', 'A size with this code already exists in our system.');
        }

        // Check if pending request already exists
        $existingRequest = SizeRequest::where('vendor_id', auth('vendor')->id())
            ->where(function ($q) use ($request) {
                $q->where('requested_name', $request->name)
                    ->orWhere('requested_code', $request->code);
            })
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this size.'
                ], 422);
            }
            return back()->with('error', 'You already have a pending request for this size.');
        }

        $sizeRequest = SizeRequest::create([
            'vendor_id' => auth('vendor')->id(),
            'requested_name' => $request->name,
            'requested_slug' => Str::slug($request->name . '-' . $request->gender),
            'requested_code' => strtoupper($request->code),
            'requested_gender' => $request->gender,
            'requested_category_ids' => $request->category_ids,
            'requested_chest' => $request->chest,
            'requested_waist' => $request->waist,
            'requested_hip' => $request->hip,
            'requested_inseam' => $request->inseam,
            'requested_shoulder' => $request->shoulder,
            'requested_sleeve' => $request->sleeve,
            'requested_neck' => $request->neck,
            'requested_height' => $request->height,
            'requested_weight' => $request->weight,
            'requested_us_size' => $request->us_size,
            'requested_uk_size' => $request->uk_size,
            'requested_eu_size' => $request->eu_size,
            'requested_au_size' => $request->au_size,
            'requested_jp_size' => $request->jp_size,
            'requested_cn_size' => $request->cn_size,
            'requested_int_size' => $request->int_size,
            'description' => $request->description,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Prepare new values for logging
        $newValues = [
            'id' => $sizeRequest->id,
            'requested_name' => $sizeRequest->requested_name,
            'requested_slug' => $sizeRequest->requested_slug,
            'requested_code' => $sizeRequest->requested_code,
            'requested_gender' => $sizeRequest->requested_gender,
            'requested_category_ids' => $sizeRequest->requested_category_ids,
            'description' => $sizeRequest->description,
            'reason' => $sizeRequest->reason,
            'status' => $sizeRequest->status,
            'vendor_id' => $sizeRequest->vendor_id,
            'created_at' => $sizeRequest->created_at ? $sizeRequest->created_at->toDateTimeString() : null,
        ];

        // Get category names for better logging
        $categoryNames = Category::whereIn('id', $request->category_ids)->pluck('name')->implode(', ');

        // Log activity using the logActivity method
        $this->logActivity(
            'request_size',                              // action
            'size_request',                              // entity_type
            $sizeRequest->id,                            // entity_id
            $sizeRequest->requested_name,                // entity_name
            null,                                        // old_values
            $newValues,                                  // new_values
            "Requested new size: {$sizeRequest->requested_name} ({$sizeRequest->requested_code}) for {$sizeRequest->requested_gender} - Categories: {$categoryNames} - Reason: " . Str::limit($request->reason, 100)
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Size request submitted successfully. Admin will review it.',
                'request' => $sizeRequest
            ]);
        }

        return redirect()->route('vendor.sizes.requests.index')
            ->with('success', 'Size request submitted successfully. Admin will review it.');
    }

    /**
     * Display vendor's size requests
     */
    public function myRequests(Request $request)
    {
        $query = SizeRequest::where('vendor_id', auth('vendor')->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('requested_gender', $request->gender);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => SizeRequest::where('vendor_id', auth('vendor')->id())->count(),
            'pending' => SizeRequest::where('vendor_id', auth('vendor')->id())->where('status', 'pending')->count(),
            'approved' => SizeRequest::where('vendor_id', auth('vendor')->id())->where('status', 'approved')->count(),
            'rejected' => SizeRequest::where('vendor_id', auth('vendor')->id())->where('status', 'rejected')->count(),
        ];

        return view('marketplace.pages.sizes.my-requests', compact('requests', 'statistics'));
    }

    /**
     * Show single request details
     */
    public function showRequest($id)
    {
        $request = SizeRequest::where('vendor_id', auth('vendor')->id())
            ->with('vendor', 'approvedBy', 'createdSize')
            ->findOrFail($id);

        return view('marketplace.pages.sizes.request-details', compact('request'));
    }

    /**
     * Cancel a pending request
     */
    public function cancelRequest($id)
    {
        $sizeRequest = SizeRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $oldValues = $sizeRequest->toArray();
        $requestName = $sizeRequest->requested_name;
        $sizeRequest->delete();

        // Log activity using the logActivity method
        $this->logActivity(
            'cancel_size_request',                       // action
            'size_request',                              // entity_type
            $id,                                         // entity_id
            $requestName,                                // entity_name
            $oldValues,                                  // old_values
            null,                                        // new_values
            "Cancelled size request: {$requestName} ({$sizeRequest->requested_code})"
        );

        return redirect()->route('vendor.sizes.requests.index')
            ->with('success', 'Size request cancelled successfully.');
    }

    /**
     * Get sizes by category for AJAX request
     */
    public function getSizesByCategory($categoryId)
    {
        $sizes = Size::whereHas('categories', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get(['id', 'name', 'code', 'gender']);

        return response()->json([
            'sizes' => $sizes
        ]);
    }
}
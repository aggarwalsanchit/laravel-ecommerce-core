<?php
// app/Http/Controllers/Vendor/VendorCategoryController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryRequest;
use App\Traits\LogsVendorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorCategoryController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new Middleware('permission:view_categories', only: ['index', 'show']),
            // new Middleware('permission:request_categories', only: ['createRequest', 'storeRequest']),
        ];
    }

    /**
     * Display list of available categories (read-only for vendors)
     */
    public function index(Request $request)
    {
        $query = Category::with('parent')
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

        // Filter by parent category
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        } else {
            // Show only top-level categories by default
            $query->whereNull('parent_id');
        }

        $categories = $query->orderBy('order')->paginate(20);

        // Get all parent categories for filter
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();

        // Get vendor's pending requests count
        $pendingRequestsCount = CategoryRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->count();

        return view('marketplace.pages.categories.index', compact('categories', 'parentCategories', 'pendingRequestsCount'));
    }

    /**
     * Show single category details
     */
    public function show($id)
    {
        $category = Category::with('parent', 'children')
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        // Get subcategories
        $subcategories = Category::where('parent_id', $category->id)
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();

        return view('marketplace.pages.categories.show', compact('category', 'subcategories'));
    }

    /**
     * Show form to request a new category
     */
    public function createRequest()
    {
        $parentCategories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();

        return view('marketplace.pages.categories.request', compact('parentCategories'));
    }

    /**
     * Store a new category request
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:category_requests,requested_name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'reason' => 'required|string|max:500',
        ]);

        // Check if category already exists
        $existingCategory = Category::where('name', $request->name)->first();
        if ($existingCategory) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This category already exists in our system.'
                ], 422);
            }
            return back()->with('error', 'This category already exists in our system.');
        }

        // Check if pending request already exists
        $existingRequest = CategoryRequest::where('vendor_id', auth('vendor')->id())
            ->where('requested_name', $request->name)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this category.'
                ], 422);
            }
            return back()->with('error', 'You already have a pending request for this category.');
        }

        $categoryRequest = CategoryRequest::create([
            'vendor_id' => auth('vendor')->id(),
            'requested_name' => $request->name,
            'requested_slug' => Str::slug($request->name),
            'requested_parent_id' => $request->parent_id,
            'description' => $request->description,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Prepare new values for logging
        $newValues = [
            'id' => $categoryRequest->id,
            'requested_name' => $categoryRequest->requested_name,
            'requested_slug' => $categoryRequest->requested_slug,
            'requested_parent_id' => $categoryRequest->requested_parent_id,
            'description' => $categoryRequest->description,
            'reason' => $categoryRequest->reason,
            'status' => $categoryRequest->status,
            'vendor_id' => $categoryRequest->vendor_id,
            'created_at' => $categoryRequest->created_at ? $categoryRequest->created_at->toDateTimeString() : null,
        ];

        // Get parent category name for better logging
        $parentName = '';
        if ($categoryRequest->requested_parent_id) {
            $parent = Category::find($categoryRequest->requested_parent_id);
            $parentName = $parent ? $parent->name : 'Unknown';
        }

        // Log activity using the logActivity method
        $this->logActivity(
            'request_category',                          // action
            'category_request',                          // entity_type
            $categoryRequest->id,                        // entity_id
            $categoryRequest->requested_name,            // entity_name
            null,                                        // old_values
            $newValues,                                  // new_values
            "Requested new category: {$categoryRequest->requested_name}" .
                ($parentName ? " under parent category: {$parentName}" : " as main category") .
                " - Reason: " . Str::limit($request->reason, 100)
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category request submitted successfully. Admin will review it.',
                'request' => $categoryRequest
            ]);
        }

        return redirect()->route('vendor.categories.requests.index')
            ->with('success', 'Category request submitted successfully. Admin will review it.');
    }

    public function getNames(Request $request)
    {
        $ids = $request->ids;
        $categories = Category::whereIn('id', $ids)->get(['id', 'name']);
        return response()->json(['categories' => $categories]);
    }

    /**
     * Display vendor's category requests
     */
    public function myRequests(Request $request)
    {
        $query = CategoryRequest::where('vendor_id', auth('vendor')->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => CategoryRequest::where('vendor_id', auth('vendor')->id())->count(),
            'pending' => CategoryRequest::where('vendor_id', auth('vendor')->id())->where('status', 'pending')->count(),
            'approved' => CategoryRequest::where('vendor_id', auth('vendor')->id())->where('status', 'approved')->count(),
            'rejected' => CategoryRequest::where('vendor_id', auth('vendor')->id())->where('status', 'rejected')->count(),
        ];

        return view('marketplace.pages.categories.my-requests', compact('requests', 'statistics'));
    }

    /**
     * Show single request details
     */
    public function showRequest($id)
    {
        $request = CategoryRequest::where('vendor_id', auth('vendor')->id())
            ->with('vendor', 'approvedBy', 'createdCategory', 'requestedParent')
            ->findOrFail($id);

        return view('marketplace.pages.categories.request-details', compact('request'));
    }

    /**
     * Cancel a pending request
     */
    public function cancelRequest($id)
    {
        $categoryRequest = CategoryRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $oldValues = $categoryRequest->toArray();
        $requestName = $categoryRequest->requested_name;
        $categoryRequest->delete();

        // Log activity using the logActivity method
        $this->logActivity(
            'cancel_category_request',                   // action
            'category_request',                          // entity_type
            $id,                                         // entity_id
            $requestName,                                // entity_name
            $oldValues,                                  // old_values
            null,                                        // new_values
            "Cancelled category request: {$requestName}"
        );

        return redirect()->route('vendor.categories.requests.index')
            ->with('success', 'Category request cancelled successfully.');
    }

    /**
     * Get subcategories for AJAX request
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get(['id', 'name']);

        return response()->json([
            'subcategories' => $subcategories
        ]);
    }

    /**
     * Get category tree for AJAX request
     */
    public function getCategoryTree()
    {
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();

        $tree = $this->buildTree($categories);

        return response()->json([
            'categories' => $tree
        ]);
    }

    /**
     * Build category tree recursively
     */
    private function buildTree($categories, $parentId = null, $depth = 0)
    {
        $result = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $children = $this->buildTree($categories, $category->id, $depth + 1);
                $result[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'depth' => $depth,
                    'children' => $children,
                    'product_count' => $category->product_count ?? 0,
                ];
            }
        }
        return $result;
    }
}

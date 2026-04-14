<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryRequest;
use App\Models\CategoryAnalytic;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class CategoryController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_categories', only: ['index', 'show', 'analytics', 'pendingRequests', 'viewRequest']),
            new Middleware('permission:create_categories', only: ['create', 'store']),
            new Middleware('permission:edit_categories', only: ['edit', 'update', 'toggleStatus', 'toggleMenu', 'toggleFeatured', 'togglePopular', 'bulkAction']),
            new Middleware('permission:approve_categories', only: ['approveRequest', 'rejectRequest']),
            new Middleware('permission:delete_categories', only: ['destroy', 'deleteRequest']),
        ];
    }

    /**
     * Display pending category requests from vendors
     */
    public function pendingRequests(Request $request)
    {
        $query = CategoryRequest::with('vendor');

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
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
            'total_pending' => CategoryRequest::where('status', 'pending')->count(),
            'total_approved' => CategoryRequest::where('status', 'approved')->count(),
            'total_rejected' => CategoryRequest::where('status', 'rejected')->count(),
            'total_requests' => CategoryRequest::count(),
        ];

        return view('admin.pages.categories.requests', compact('requests', 'statistics', 'status'));
    }

    /**
     * View single category request details
     */
    public function viewRequest($id)
    {
        $categoryRequest = CategoryRequest::with('vendor', 'requestedParent', 'approvedBy', 'createdCategory')->findOrFail($id);
        return view('admin.pages.categories.request-details', compact('categoryRequest'));
    }

    /**
     * Approve a category request and create the category
     */
    public function approveRequest(Request $request, $id)
    {
        $categoryRequest = CategoryRequest::with('vendor')->findOrFail($id);

        if ($categoryRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        // Check if category already exists
        $existingCategory = Category::where('name', $categoryRequest->requested_name)->first();
        if ($existingCategory) {
            return response()->json([
                'success' => false,
                'message' => 'A category with name "' . $categoryRequest->requested_name . '" already exists. Please reject this request.'
            ], 422);
        }

        // Create the category in main categories table
        $category = Category::create([
            'name' => $categoryRequest->requested_name,
            'slug' => Str::slug($categoryRequest->requested_name),
            'description' => $categoryRequest->description,
            'parent_id' => $categoryRequest->requested_parent_id,
            'image' => $categoryRequest->image,
            'requested_by' => $categoryRequest->vendor_id,
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
            'order' => Category::max('order') + 1,
        ]);

        // Update the request
        $categoryRequest->update([
            'status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'created_category_id' => $category->id,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'approve_category_request',
            'category_request',
            'admin',
            $categoryRequest->id,
            $categoryRequest->requested_name,
            null,
            [
                'request_id' => $categoryRequest->id,
                'category_id' => $category->id,
                'vendor_id' => $categoryRequest->vendor_id,
                'vendor_name' => $categoryRequest->vendor->name ?? 'Unknown',
            ],
            "Approved category request '{$categoryRequest->requested_name}' from Vendor #{$categoryRequest->vendor_id} and created category"
        );

        return response()->json([
            'success' => true,
            'message' => 'Category request approved and category created successfully.',
            'category' => $category
        ]);
    }

    /**
     * Reject a category request
     */
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $categoryRequest = CategoryRequest::findOrFail($id);

        if ($categoryRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $categoryRequest->update([
            'status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'reject_category_request',
            'category_request',
            'admin',
            $categoryRequest->id,
            $categoryRequest->requested_name,
            null,
            [
                'request_id' => $categoryRequest->id,
                'vendor_id' => $categoryRequest->vendor_id,
                'rejection_reason' => $request->rejection_reason,
            ],
            "Rejected category request '{$categoryRequest->requested_name}' - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Category request rejected successfully.'
        ]);
    }

    /**
     * Delete a category request (for rejected/spam requests)
     */
    public function deleteRequest($id)
    {
        $categoryRequest = CategoryRequest::findOrFail($id);

        if ($categoryRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved requests. Delete the category instead.'
            ], 422);
        }

        $requestName = $categoryRequest->requested_name;
        $categoryRequest->delete();

        $this->logActivity(
            'delete_category_request',
            'category_request',
            'admin',
            $id,
            $requestName,
            null,
            null,
            "Deleted category request '{$requestName}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Category request deleted successfully.'
        ]);
    }

    /**
     * Bulk action on category requests
     */
    public function bulkRequestAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'request_ids' => 'required|string',
        ]);

        $action = $request->action;
        $requestIds = json_decode($request->request_ids);
        $requests = CategoryRequest::whereIn('id', $requestIds)->get();

        $count = 0;
        $errors = [];
        $processedRequests = [];

        foreach ($requests as $categoryRequest) {
            try {
                if ($categoryRequest->status !== 'pending' && in_array($action, ['approve', 'reject'])) {
                    $errors[] = "Request '{$categoryRequest->requested_name}' is already processed.";
                    continue;
                }

                switch ($action) {
                    case 'approve':
                        // Check if category already exists
                        $existingCategory = Category::where('name', $categoryRequest->requested_name)->first();
                        if ($existingCategory) {
                            $errors[] = "Category '{$categoryRequest->requested_name}' already exists.";
                            continue 2;
                        }

                        $category = Category::create([
                            'name' => $categoryRequest->requested_name,
                            'slug' => Str::slug($categoryRequest->requested_name),
                            'description' => $categoryRequest->description,
                            'parent_id' => $categoryRequest->requested_parent_id,
                            'image' => $categoryRequest->image,
                            'requested_by' => $categoryRequest->vendor_id,
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'approval_status' => 'approved',
                            'status' => true,
                            'order' => Category::max('order') + 1,
                        ]);

                        $categoryRequest->update([
                            'status' => 'approved',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'created_category_id' => $category->id,
                        ]);
                        $count++;
                        $processedRequests[] = $categoryRequest->requested_name;
                        break;

                    case 'reject':
                        $categoryRequest->update([
                            'status' => 'rejected',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'rejection_reason' => $request->rejection_reason ?? 'Bulk rejection',
                        ]);
                        $count++;
                        $processedRequests[] = $categoryRequest->requested_name;
                        break;

                    case 'delete':
                        if ($categoryRequest->status === 'approved') {
                            $errors[] = "Cannot delete approved request '{$categoryRequest->requested_name}'.";
                            continue 2;
                        }
                        $categoryRequest->delete();
                        $count++;
                        $processedRequests[] = $categoryRequest->requested_name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$categoryRequest->requested_name}': " . $e->getMessage();
            }
        }

        // Log bulk action
        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action . '_requests',
                'category_request',
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
                "Bulk {$action} performed on {$count} category requests"
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
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::with('parent');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter by type (main or sub)
        if ($request->filled('type')) {
            if ($request->type === 'main') {
                $query->whereNull('parent_id');
            } elseif ($request->type === 'sub') {
                $query->whereNotNull('parent_id');
            }
        }

        // Filter by featured/popular
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'true');
        }
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'true');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('order', 'asc');
        }

        $categories = $query->paginate(15);

        // Add depth to each category for indentation
        foreach ($categories as $category) {
            $category->depth = $this->getCategoryDepth($category);

            // Get analytics from separate table
            $analytics = CategoryAnalytic::where('category_id', $category->id)
                ->where('date', today()->toDateString())
                ->first();

            $category->view_count = $analytics->view_count ?? 0;
            $category->product_count = $analytics->product_count ?? 0;
            $category->order_count = $analytics->order_count ?? 0;
            $category->total_revenue = $analytics->total_revenue ?? 0;
        }

        // Statistics from categories table (not analytics)
        $statistics = [
            'total' => Category::count(),
            'active' => Category::where('status', true)->count(),
            'featured' => Category::where('is_featured', true)->count(),
            'pending' => Category::where('approval_status', 'pending')->count(),
            'rejected' => Category::where('approval_status', 'rejected')->count(),
            'total_views' => CategoryAnalytic::sum('view_count'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.categories.partials.categories-table', compact('categories'))->render();
            $pagination = $categories->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.categories.index', compact('categories', 'statistics'));
    }

    /**
     * Get category depth for indentation
     */
    private function getCategoryDepth($category, $depth = 0)
    {
        if (!$category->parent) {
            return $depth;
        }
        return $this->getCategoryDepth($category->parent, $depth + 1);
    }

    /**
     * Category Analytics Dashboard
     */
    public function analytics()
    {
        // Get top categories by views from analytics table (last 30 days aggregate)
        $topViewsCategories = Category::select('categories.id', 'categories.name', 'categories.slug')
            ->join('category_analytics', 'categories.id', '=', 'category_analytics.category_id')
            ->selectRaw('SUM(category_analytics.view_count) as total_views')
            ->where('categories.status', true)
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();

        // Top categories by revenue from analytics table
        $topRevenueCategories = Category::select('categories.id', 'categories.name', 'categories.slug')
            ->join('category_analytics', 'categories.id', '=', 'category_analytics.category_id')
            ->selectRaw('SUM(category_analytics.total_revenue) as total_revenue')
            ->where('categories.status', true)
            ->where('category_analytics.total_revenue', '>', 0)
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Categories with most products from analytics table
        $topProductCategories = Category::select('categories.id', 'categories.name', 'categories.slug')
            ->join('category_analytics', 'categories.id', '=', 'category_analytics.category_id')
            ->selectRaw('AVG(category_analytics.product_count) as avg_products')
            ->where('categories.status', true)
            ->where('category_analytics.product_count', '>', 0)
            ->groupBy('categories.id', 'categories.name', 'categories.slug')
            ->orderBy('avg_products', 'desc')
            ->take(10)
            ->get();

        // Pending approval categories
        $pendingCategories = Category::where('approval_status', 'pending')
            ->with('requestedBy')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Rejected categories
        $rejectedCategories = Category::where('approval_status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', true)->count();
        $inactiveCategories = $totalCategories - $activeCategories;
        $featuredCategories = Category::where('is_featured', true)->count();
        $popularCategories = Category::where('is_popular', true)->count();
        $pendingCount = Category::where('approval_status', 'pending')->count();
        $approvedCount = Category::where('approval_status', 'approved')->count();
        $rejectedCount = Category::where('approval_status', 'rejected')->count();

        // Analytics totals from analytics table
        $totalViews = CategoryAnalytic::sum('view_count');
        $totalProducts = CategoryAnalytic::sum('product_count');
        $totalRevenue = CategoryAnalytic::sum('total_revenue');

        // Growth data for last 30 days from analytics table
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = CategoryAnalytic::whereDate('date', $date)->sum('product_count');
            $growthData[] = $count;
        }

        // Parent/Child stats
        $parentCategories = Category::whereNull('parent_id')->count();
        $childCategories = Category::whereNotNull('parent_id')->count();

        // Averages
        $avgProductsPerCategory = $totalCategories > 0 ? round($totalProducts / $totalCategories, 1) : 0;
        $avgViewsPerCategory = $totalCategories > 0 ? round($totalViews / $totalCategories, 1) : 0;

        return view('admin.pages.categories.analytics', compact(
            'topViewsCategories',
            'topRevenueCategories',
            'topProductCategories',
            'pendingCategories',
            'rejectedCategories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories',
            'featuredCategories',
            'popularCategories',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'growthData',
            'growthLabels',
            'parentCategories',
            'childCategories',
            'avgProductsPerCategory',
            'avgViewsPerCategory',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show form for creating new category.
     */
    public function create()
    {
        $categories = Category::orderBy('order')->get();
        return view('admin.pages.categories.create', compact('categories'));
    }

    /**
     * Store newly created category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'show_in_menu' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
        ]);

        $data = $request->except(['image', 'thumbnail_image', 'banner_image']);

        // Handle main image
        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'categories', 800, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
                $data['image_alt'] = $request->image_alt ?? $request->name;
            }
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail_image')) {
            $compressed = $this->imageCompressor->compress($request->file('thumbnail_image'), 'categories/thumbnails', 150, 80);
            if ($compressed['success']) {
                $data['thumbnail_image'] = $compressed['filename'];
                $data['thumbnail_alt'] = $request->thumbnail_alt ?? $request->name;
            }
        }

        // Handle banner
        if ($request->hasFile('banner_image')) {
            $compressed = $this->imageCompressor->compress($request->file('banner_image'), 'categories/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner_image'] = $compressed['filename'];
                $data['banner_alt'] = $request->banner_alt ?? $request->name;
            }
        }

        $data['slug'] = Str::slug($request->name);
        $data['approval_status'] = 'approved';

        $category = Category::create($data);

        // Log activity
        $this->logActivity(
            'create',
            'category',
            'admin',
            $category->id,
            $category->name,
            null,
            $category->toArray(),
            "Created new category: {$category->name}" . ($category->parent ? " under {$category->parent->name}" : " as main category")
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display category details.
     */
    public function show(Category $category)
    {
        $category->load('parent', 'children', 'requestedBy', 'approvedBy');

        // Get recent analytics
        $recentAnalytics = CategoryAnalytic::where('category_id', $category->id)
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('admin.pages.categories.show', compact('category', 'recentAnalytics'));
    }

    /**
     * Show form for editing category.
     */
    public function edit(Category $category)
    {
        // $category is already the model you want to edit
        // Get ALL OTHER categories for parent selection (excluding current category)
        $categories = Category::where('id', '!=', $category->id)
            ->orderBy('order')
            ->get();

        return view('admin.pages.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update category.
     */
    public function update(Request $request, Category $category)
    {
        $oldData = $category->toArray();

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'show_in_menu' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
        ]);

        $data = $request->except(['image', 'thumbnail_image', 'banner_image', 'remove_image', 'remove_thumbnail', 'remove_banner']);

        // Handle main image
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('categories/' . $category->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'categories', 800, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
                $data['image_alt'] = $request->image_alt ?? $request->name;
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('categories/' . $category->image);
            $data['image'] = null;
            $data['image_alt'] = null;
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail_image')) {
            $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
            $compressed = $this->imageCompressor->compress($request->file('thumbnail_image'), 'categories/thumbnails', 150, 80);
            if ($compressed['success']) {
                $data['thumbnail_image'] = $compressed['filename'];
                $data['thumbnail_alt'] = $request->thumbnail_alt ?? $request->name;
            }
        } elseif ($request->has('remove_thumbnail') && $request->remove_thumbnail) {
            $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
            $data['thumbnail_image'] = null;
            $data['thumbnail_alt'] = null;
        }

        // Handle banner
        if ($request->hasFile('banner_image')) {
            $this->deleteImageIfExists('categories/banners/' . $category->banner_image);
            $compressed = $this->imageCompressor->compress($request->file('banner_image'), 'categories/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner_image'] = $compressed['filename'];
                $data['banner_alt'] = $request->banner_alt ?? $request->name;
            }
        } elseif ($request->has('remove_banner') && $request->remove_banner) {
            $this->deleteImageIfExists('categories/banners/' . $category->banner_image);
            $data['banner_image'] = null;
            $data['banner_alt'] = null;
        }

        $category->update($data);

        // Log activity
        $changes = $this->getChanges($oldData, $category->toArray());
        $this->logActivity(
            'update',
            'category',
            'admin',
            $category->id,
            $category->name,
            $oldData,
            $category->toArray(),
            "Updated category: {$category->name}" . (!empty($changes) ? " - Changes: " . implode(', ', $changes) : "")
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.'
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Approve pending category (for vendor requests)
     */
    public function approveCategory(Category $category)
    {
        if ($category->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This category is not pending approval.'
            ], 422);
        }

        $oldStatus = $category->approval_status;
        $category->update([
            'approval_status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'status' => true
        ]);

        // Log activity
        $this->logActivity(
            'approve',
            'category',
            'admin',
            $category->id,
            $category->name,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'approved'],
            "Approved category: {$category->name} (Previously requested by vendor)"
        );

        return response()->json([
            'success' => true,
            'message' => 'Category approved successfully.'
        ]);
    }

    /**
     * Reject pending category (for vendor requests)
     */
    public function rejectCategory(Request $request, Category $category)
    {
        if ($category->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This category is not pending approval.'
            ], 422);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $oldStatus = $category->approval_status;
        $category->update([
            'approval_status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'status' => false
        ]);

        // Log activity
        $this->logActivity(
            'reject',
            'category',
            'admin',
            $category->id,
            $category->name,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'rejected', 'rejection_reason' => $request->rejection_reason],
            "Rejected category: {$category->name} - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Category rejected successfully.'
        ]);
    }

    /**
     * Delete category.
     */
    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category because it has ' . $category->children()->count() . ' subcategories.'
            ], 422);
        }

        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category because it has ' . $category->products()->count() . ' products.'
            ], 422);
        }

        $categoryData = $category->toArray();
        $categoryName = $category->name;

        // Delete images
        $this->deleteImageIfExists('categories/' . $category->image);
        $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
        $this->deleteImageIfExists('categories/banners/' . $category->banner_image);

        // Delete analytics records
        CategoryAnalytic::where('category_id', $category->id)->delete();

        $category->delete();

        // Log activity
        $this->logActivity(
            'delete',
            'category',
            'admin',
            $category->id,
            $categoryName,
            $categoryData,
            null,
            "Deleted category: {$categoryName}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }

    /**
     * Get subcategories for a category
     */
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_id', $categoryId)
            ->where('status', true)
            ->orderBy('order')
            ->get(['id', 'name']);

        return response()->json([
            'subcategories' => $subcategories
        ]);
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category)
    {
        $oldStatus = $category->status;
        $category->update(['status' => !$category->status]);

        $this->logActivity(
            'toggle_status',
            'category',
            'admin',
            $category->id,
            $category->name,
            ['status' => $oldStatus],
            ['status' => $category->status],
            "Toggled category status for '{$category->name}' from " . ($oldStatus ? 'Active' : 'Inactive') . " to " . ($category->status ? 'Active' : 'Inactive')
        );

        return response()->json([
            'success' => true,
            'message' => 'Category status updated.',
            'status' => $category->status
        ]);
    }

    /**
     * Toggle menu visibility.
     */
    public function toggleMenu(Category $category)
    {
        $oldValue = $category->show_in_menu;
        $category->update(['show_in_menu' => !$category->show_in_menu]);

        $this->logActivity(
            'toggle_menu',
            'category',
            'admin',
            $category->id,
            $category->name,
            ['show_in_menu' => $oldValue],
            ['show_in_menu' => $category->show_in_menu],
            "Toggled menu visibility for '{$category->name}' to " . ($category->show_in_menu ? 'Show' : 'Hide')
        );

        return response()->json([
            'success' => true,
            'message' => 'Menu visibility updated.',
            'show_in_menu' => $category->show_in_menu
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Category $category)
    {
        $oldValue = $category->is_featured;
        $category->update(['is_featured' => !$category->is_featured]);

        $this->logActivity(
            'toggle_featured',
            'category',
            'admin',
            $category->id,
            $category->name,
            ['is_featured' => $oldValue],
            ['is_featured' => $category->is_featured],
            ($category->is_featured ? 'Marked' : 'Unmarked') . " category '{$category->name}' as featured"
        );

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $category->is_featured
        ]);
    }

    /**
     * Toggle popular status.
     */
    public function togglePopular(Category $category)
    {
        $oldValue = $category->is_popular;
        $category->update(['is_popular' => !$category->is_popular]);

        $this->logActivity(
            'toggle_popular',
            'category',
            'admin',
            $category->id,
            $category->name,
            ['is_popular' => $oldValue],
            ['is_popular' => $category->is_popular],
            ($category->is_popular ? 'Marked' : 'Unmarked') . " category '{$category->name}' as popular"
        );

        return response()->json([
            'success' => true,
            'message' => 'Popular status updated.',
            'is_popular' => $category->is_popular
        ]);
    }

    /**
     * Bulk action on categories
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,popular,unpopular,approve,reject',
            'category_ids' => 'required|string',
        ]);

        $action = $request->action;
        $categoryIds = json_decode($request->category_ids);

        // Check permissions based on action
        if (in_array($action, ['activate', 'deactivate', 'feature', 'unfeature', 'popular', 'unpopular', 'approve', 'reject'])) {
            if (!auth('admin')->user()->can('edit_categories')) {
                return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
            }
        }

        if ($action === 'delete') {
            if (!auth('admin')->user()->can('delete_categories')) {
                return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
            }
        }

        $categories = Category::whereIn('id', $categoryIds)->get();
        $count = 0;
        $errors = [];
        $processedCategories = [];

        foreach ($categories as $category) {
            try {
                $oldData = $category->toArray();

                switch ($action) {
                    case 'activate':
                        $category->update(['status' => true]);
                        $count++;
                        $processedCategories[] = $category->name;
                        break;

                    case 'deactivate':
                        $category->update(['status' => false]);
                        $count++;
                        $processedCategories[] = $category->name;
                        break;

                    case 'feature':
                        $category->update(['is_featured' => true]);
                        $count++;
                        $processedCategories[] = $category->name;
                        break;

                    case 'unfeature':
                        $category->update(['is_featured' => false]);
                        $count++;
                        $processedCategories[] = $category->name;
                        break;

                    case 'popular':
                        $category->update(['is_popular' => true]);
                        $count++;
                        $processedCategories[] = $category->name;
                        break;

                    case 'unpopular':
                        $category->update(['is_popular' => false]);
                        $count++;
                        $processedCategories[] = $category->name;
                        break;

                    case 'approve':
                        if ($category->approval_status === 'pending') {
                            $category->update([
                                'approval_status' => 'approved',
                                'approved_by' => auth('admin')->id(),
                                'approved_at' => now(),
                                'status' => true
                            ]);
                            $count++;
                            $processedCategories[] = $category->name;
                        }
                        break;

                    case 'reject':
                        if ($category->approval_status === 'pending') {
                            $category->update([
                                'approval_status' => 'rejected',
                                'approved_by' => auth('admin')->id(),
                                'approved_at' => now(),
                                'status' => false
                            ]);
                            $count++;
                            $processedCategories[] = $category->name;
                        }
                        break;

                    case 'delete':
                        if ($category->children()->count() > 0) {
                            $errors[] = "Cannot delete '{$category->name}' because it has subcategories.";
                            continue 2;
                        }
                        if ($category->products()->count() > 0) {
                            $errors[] = "Cannot delete '{$category->name}' because it has products.";
                            continue 2;
                        }

                        // Delete images
                        $this->deleteImageIfExists('categories/' . $category->image);
                        $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
                        $this->deleteImageIfExists('categories/banners/' . $category->banner_image);

                        // Delete analytics records
                        CategoryAnalytic::where('category_id', $category->id)->delete();

                        $category->delete();
                        $count++;
                        $processedCategories[] = $category->name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$category->name}': " . $e->getMessage();
            }
        }

        // Log bulk action
        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action,
                'category',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_categories' => $processedCategories,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} categories: " . implode(', ', $processedCategories)
            );
        }

        $message = "{$count} categories processed successfully.";
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
     * Quick store for AJAX category creation
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'slug' => Str::slug($request->name),
            'status' => $request->status ?? true,
            'approval_status' => 'approved'
        ]);

        // Log activity
        $this->logActivity(
            'quick_create',
            'category',
            'admin',
            $category->id,
            $category->name,
            null,
            $category->toArray(),
            "Quick created category: {$category->name}"
        );

        // Get the updated list of categories for dropdown
        $allCategories = Category::where('status', true)
            ->orderBy('name')
            ->get();

        // Build hierarchical dropdown options
        $dropdownOptions = $this->buildCategoryDropdown($allCategories);

        // Get main categories for main dropdown
        $mainCategories = Category::whereNull('parent_id')
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'category' => $category,
            'main_categories' => $mainCategories,
            'all_categories' => $dropdownOptions,
            'message' => 'Category added successfully'
        ]);
    }

    /**
     * Build category dropdown for select inputs
     */
    private function buildCategoryDropdown($categories, $parentId = null, $depth = 0)
    {
        $options = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $prefix = str_repeat('— ', $depth);
                $options[] = [
                    'id' => $category->id,
                    'name' => $prefix . $category->name,
                    'depth' => $depth
                ];
                $children = $this->buildCategoryDropdown($categories, $category->id, $depth + 1);
                $options = array_merge($options, $children);
            }
        }
        return $options;
    }

    /**
     * Delete image if exists
     */
    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get changes between old and new data
     */
    private function getChanges($oldData, $newData)
    {
        $changes = [];
        $fields = ['name', 'parent_id', 'status', 'is_featured', 'is_popular', 'show_in_menu', 'order'];

        foreach ($fields as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] != $newData[$field]) {
                $changes[] = $field . " changed from '{$oldData[$field]}' to '{$newData[$field]}'";
            }
        }

        return $changes;
    }
}

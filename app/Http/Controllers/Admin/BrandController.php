<?php
// app/Http/Controllers/Admin/BrandController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandDailyAnalytic;
use App\Models\BrandRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BrandController extends Controller implements HasMiddleware
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
            new Middleware('permission:view_brands', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create_brands', only: ['create', 'store']),
            new Middleware('permission:edit_brands', only: ['edit', 'update', 'toggleStatus', 'toggleFeatured', 'bulkAction']),
            new Middleware('permission:delete_brands', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of brands.
     */
    public function index(Request $request)
    {
        $query = Brand::with(['categories', 'products', 'dailyAnalytics']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        // Featured filter
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'code':
                $query->orderBy('code', $sortOrder);
                break;
            case 'products_count':
                $query->withCount('products')->orderBy('products_count', $sortOrder);
                break;
            default:
                $query->orderBy('order', 'asc');
        }

        $brands = $query->paginate(15);

        // Add aggregated analytics using relationship
        foreach ($brands as $brand) {
            $brand->products_count = $brand->products()->count();
            $brand->total_views = $brand->dailyAnalytics->sum('view_count');
            $brand->total_orders = $brand->dailyAnalytics->sum('order_count');
            $brand->total_revenue = $brand->dailyAnalytics->sum('total_revenue');
            $brand->avg_rating = $brand->dailyAnalytics->avg('avg_rating') ?? 0;
            $brand->review_count = $brand->dailyAnalytics->sum('review_count');
        }

        // Statistics using relationships
        $statistics = [
            'total' => Brand::count(),
            'active' => Brand::where('status', true)->count(),
            'inactive' => Brand::where('status', false)->count(),
            'featured' => Brand::where('is_featured', true)->count(),
            'total_products' => Product::whereNotNull('brand_id')->count(),
            'total_views' => BrandDailyAnalytic::sum('view_count'),
            'total_revenue' => BrandDailyAnalytic::sum('total_revenue'),
            'total_orders' => BrandDailyAnalytic::sum('order_count'),
        ];

        // Get pending requests count
        $pendingRequestsCount = BrandRequest::where('status', 'pending')->count();

        if ($request->ajax()) {
            $table = view('admin.pages.brands.partials.brands-table', compact('brands'))->render();
            $pagination = $brands->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.brands.index', compact('brands', 'statistics', 'pendingRequestsCount'));
    }

    /**
     * Brand Analytics Dashboard
     */
    public function analytics(Request $request)
    {
        // Date range
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Get analytics with eager loading
        $analytics = BrandDailyAnalytic::with('brand')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Top brands by revenue using relationship
        $topRevenueBrands = Brand::with(['dailyAnalytics' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])
            ->where('status', true)
            ->get()
            ->map(function ($brand) {
                $brand->total_revenue = $brand->dailyAnalytics->sum('total_revenue');
                $brand->total_orders = $brand->dailyAnalytics->sum('order_count');
                $brand->total_views = $brand->dailyAnalytics->sum('view_count');
                return $brand;
            })
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values();

        // Top brands by views using relationship
        $topViewsBrands = Brand::with(['dailyAnalytics' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])
            ->where('status', true)
            ->get()
            ->map(function ($brand) {
                $brand->total_views = $brand->dailyAnalytics->sum('view_count');
                $brand->total_orders = $brand->dailyAnalytics->sum('order_count');
                $brand->total_revenue = $brand->dailyAnalytics->sum('total_revenue');
                return $brand;
            })
            ->sortByDesc('total_views')
            ->take(10)
            ->values();

        // Top brands by orders using relationship
        $topOrdersBrands = Brand::with(['dailyAnalytics' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])
            ->where('status', true)
            ->get()
            ->map(function ($brand) {
                $brand->total_orders = $brand->dailyAnalytics->sum('order_count');
                $brand->total_revenue = $brand->dailyAnalytics->sum('total_revenue');
                $brand->total_views = $brand->dailyAnalytics->sum('view_count');
                return $brand;
            })
            ->sortByDesc('total_orders')
            ->take(10)
            ->values();

        // Featured brands with product count
        $featuredBrands = Brand::where('is_featured', true)
            ->where('status', true)
            ->withCount('products')
            ->orderBy('order')
            ->take(10)
            ->get();

        // Statistics
        $totalBrands = Brand::count();
        $activeBrands = Brand::where('status', true)->count();
        $inactiveBrands = $totalBrands - $activeBrands;
        $featuredBrandsCount = Brand::where('is_featured', true)->count();
        $totalProducts = Product::whereNotNull('brand_id')->count();

        // Analytics totals using relationships
        $totalViews = BrandDailyAnalytic::whereBetween('date', [$startDate, $endDate])->sum('view_count');
        $totalOrders = BrandDailyAnalytic::whereBetween('date', [$startDate, $endDate])->sum('order_count');
        $totalRevenue = BrandDailyAnalytic::whereBetween('date', [$startDate, $endDate])->sum('total_revenue');

        // Chart data
        $dailyAnalytics = BrandDailyAnalytic::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('date');

        $chartLabels = [];
        $chartViewsData = [];
        $chartOrdersData = [];
        $chartRevenueData = [];

        $period = now()->parse($startDate)->diffInDays(now()->parse($endDate));
        for ($i = 0; $i <= $period; $i++) {
            $date = now()->parse($startDate)->addDays($i)->format('Y-m-d');
            $chartLabels[] = now()->parse($startDate)->addDays($i)->format('M d');

            $dayData = $dailyAnalytics->get($date, collect());
            $chartViewsData[] = $dayData->sum('view_count');
            $chartOrdersData[] = $dayData->sum('order_count');
            $chartRevenueData[] = $dayData->sum('total_revenue');
        }

        return view('admin.pages.brands.analytics', compact(
            'topRevenueBrands',
            'topViewsBrands',
            'topOrdersBrands',
            'featuredBrands',
            'totalBrands',
            'activeBrands',
            'inactiveBrands',
            'featuredBrandsCount',
            'totalProducts',
            'totalViews',
            'totalOrders',
            'totalRevenue',
            'chartLabels',
            'chartViewsData',
            'chartOrdersData',
            'chartRevenueData',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display brand details.
     */
    public function show(Brand $brand)
    {
        $brand->load(['categories', 'dailyAnalytics']);

        // Load products with limit
        $brand->load(['products' => function ($query) {
            $query->limit(10)->orderBy('created_at', 'desc');
        }]);

        // Get analytics from dailyAnalytics relationship
        $totalProducts = $brand->products()->count();
        $totalViews = $brand->dailyAnalytics->sum('view_count');
        $totalOrders = $brand->dailyAnalytics->sum('order_count');
        $totalRevenue = $brand->dailyAnalytics->sum('total_revenue');
        $avgRating = $brand->dailyAnalytics->avg('avg_rating') ?? 0;
        $reviewCount = $brand->dailyAnalytics->sum('review_count');

        // Get last 30 days analytics for chart
        $recentAnalytics = $brand->dailyAnalytics()
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        // Get category stats - FIXED: Products don't have direct category_id
        // Products are likely linked to categories via product_categories pivot table
        $categoryStats = [];
        foreach ($brand->categories as $category) {
            // Get products count for this brand and category
            // Using the relationship through product_categories pivot table
            $productsCount = $brand->products()
                ->whereHas('categories', function ($query) use ($category) {
                    $query->where('categories.id', $category->id);
                })
                ->count();

            $categoryStats[] = [
                'category' => $category,
                'products_count' => $productsCount
            ];
        }

        return view('admin.pages.brands.show', compact(
            'brand',
            'totalProducts',
            'totalViews',
            'totalOrders',
            'totalRevenue',
            'avgRating',
            'reviewCount',
            'recentAnalytics',
            'categoryStats'
        ));
    }

    /**
     * Get brands by category ID (AJAX)
     */
    public function getBrandsByCategory($categoryId)
    {
        $brands = Brand::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json([
            'success' => true,
            'brands' => $brands
        ]);
    }

    /**
     * Show form for creating new brand.
     */
    public function create()
    {
        $categories = Category::where('status', true)->where('parent_id', NULL)
            ->orderBy('order')
            ->get();
        return view('admin.pages.brands.create', compact('categories'));
    }

    /**
     * Store newly created brand.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'code' => 'nullable|string|max:50|unique:brands,code',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        DB::beginTransaction();

        // try {
        $data = $request->except(['logo', 'banner', 'categories']);

        // Generate slug if not set
        $data['slug'] = Str::slug($request->name);

        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = strtoupper(Str::slug($request->name, ''));
        }

        // Handle logo upload with compression
        if ($request->hasFile('logo')) {
            $compressed = $this->imageCompressor->compress($request->file('logo'), 'brands', 200, 85);
            if ($compressed['success']) {
                $data['logo'] = $compressed['filename'];
                $data['logo_alt'] = $request->logo_alt ?? $request->name;
            }
        }

        // Handle banner upload with compression
        if ($request->hasFile('banner')) {
            $compressed = $this->imageCompressor->compress($request->file('banner'), 'brands/banners', 1200, 85);
            if ($compressed['success']) {
                $data['banner'] = $compressed['filename'];
                $data['banner_alt'] = $request->banner_alt ?? $request->name;
            }
        }

        $brand = Brand::create($data);

        // Attach categories using relationship
        if ($request->has('categories')) {
            $brand->categories()->sync($request->categories);
        }

        DB::commit();

        // Log activity
        $this->logActivity(
            'create',
            'brand',
            'admin',
            $brand->id,
            $brand->name,
            null,
            $brand->toArray(),
            "Created new brand: {$brand->name} (Code: {$brand->code})"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully.',
                'brand' => $brand
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
        // } catch (\Exception $e) {
        //     DB::rollBack();

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to create brand: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Show form for editing brand.
     */
    public function edit(Brand $brand)
    {
        $brand->load('categories');
        $categories = Category::where('status', true)->orderBy('order')->get();
        $brandCategoryIds = $brand->categories->pluck('id')->toArray();

        return view('admin.pages.brands.edit', compact('brand', 'categories', 'brandCategoryIds'));
    }

    /**
     * Update brand.
     */
    public function update(Request $request, Brand $brand)
    {
        $oldData = $brand->toArray();

        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'code' => 'nullable|string|max:50|unique:brands,code,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except(['logo', 'banner', 'categories', 'remove_logo', 'remove_banner']);

            // Handle logo
            if ($request->hasFile('logo')) {
                // Delete old logo
                $this->deleteImageIfExists('brands/' . $brand->logo);

                $compressed = $this->imageCompressor->compress($request->file('logo'), 'brands', 200, 85);
                if ($compressed['success']) {
                    $data['logo'] = $compressed['filename'];
                    $data['logo_alt'] = $request->logo_alt ?? $request->name;
                }
            } elseif ($request->has('remove_logo') && $request->remove_logo) {
                $this->deleteImageIfExists('brands/' . $brand->logo);
                $data['logo'] = null;
                $data['logo_alt'] = null;
            }

            // Handle banner
            if ($request->hasFile('banner')) {
                // Delete old banner
                $this->deleteImageIfExists('brands/banners/' . $brand->banner);

                $compressed = $this->imageCompressor->compress($request->file('banner'), 'brands/banners', 1200, 85);
                if ($compressed['success']) {
                    $data['banner'] = $compressed['filename'];
                    $data['banner_alt'] = $request->banner_alt ?? $request->name;
                }
            } elseif ($request->has('remove_banner') && $request->remove_banner) {
                $this->deleteImageIfExists('brands/banners/' . $brand->banner);
                $data['banner'] = null;
                $data['banner_alt'] = null;
            }

            $brand->update($data);

            // Sync categories using relationship
            if ($request->has('categories')) {
                $brand->categories()->sync($request->categories);
            } else {
                $brand->categories()->sync([]);
            }

            DB::commit();

            // Log activity
            $changes = $this->getChanges($oldData, $brand->toArray());
            $this->logActivity(
                'update',
                'brand',
                'admin',
                $brand->id,
                $brand->name,
                $oldData,
                $brand->toArray(),
                "Updated brand: {$brand->name}" . (!empty($changes) ? " - Changes: " . implode(', ', $changes) : "")
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand updated successfully.'
                ]);
            }

            return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update brand: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete brand.
     */
    public function destroy(Brand $brand)
    {
        // Check if brand has products
        if ($brand->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete brand because it has ' . $brand->products()->count() . ' products assigned.'
            ], 422);
        }

        $brandData = $brand->toArray();
        $brandName = $brand->name;

        // Delete images
        $this->deleteImageIfExists('brands/' . $brand->logo);
        $this->deleteImageIfExists('brands/banners/' . $brand->banner);

        // Delete analytics records using relationship
        $brand->dailyAnalytics()->delete();

        // Detach categories using relationship
        $brand->categories()->detach();

        $brand->delete();

        // Log activity
        $this->logActivity(
            'delete',
            'brand',
            'admin',
            $brand->id,
            $brandName,
            $brandData,
            null,
            "Deleted brand: {$brandName}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    }

    /**
     * Toggle brand status.
     */
    public function toggleStatus(Brand $brand)
    {
        $oldStatus = $brand->status;
        $brand->update(['status' => !$brand->status]);

        $this->logActivity(
            'toggle_status',
            'brand',
            'admin',
            $brand->id,
            $brand->name,
            ['status' => $oldStatus],
            ['status' => $brand->status],
            "Toggled brand status for '{$brand->name}' from " . ($oldStatus ? 'Active' : 'Inactive') . " to " . ($brand->status ? 'Active' : 'Inactive')
        );

        return response()->json([
            'success' => true,
            'message' => 'Brand status updated.',
            'status' => $brand->status
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Brand $brand)
    {
        $oldValue = $brand->is_featured;
        $brand->update(['is_featured' => !$brand->is_featured]);

        $this->logActivity(
            'toggle_featured',
            'brand',
            'admin',
            $brand->id,
            $brand->name,
            ['is_featured' => $oldValue],
            ['is_featured' => $brand->is_featured],
            ($brand->is_featured ? 'Marked' : 'Unmarked') . " brand '{$brand->name}' as featured"
        );

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $brand->is_featured
        ]);
    }

    /**
     * Bulk action on brands
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'brand_ids' => 'required|string',
        ]);

        $action = $request->action;
        $brandIds = json_decode($request->brand_ids);

        $brands = Brand::whereIn('id', $brandIds)->get();
        $count = 0;
        $errors = [];
        $processedBrands = [];

        foreach ($brands as $brand) {
            try {
                $oldData = $brand->toArray();

                switch ($action) {
                    case 'activate':
                        $brand->update(['status' => true]);
                        $count++;
                        $processedBrands[] = $brand->name;
                        break;

                    case 'deactivate':
                        $brand->update(['status' => false]);
                        $count++;
                        $processedBrands[] = $brand->name;
                        break;

                    case 'feature':
                        $brand->update(['is_featured' => true]);
                        $count++;
                        $processedBrands[] = $brand->name;
                        break;

                    case 'unfeature':
                        $brand->update(['is_featured' => false]);
                        $count++;
                        $processedBrands[] = $brand->name;
                        break;

                    case 'delete':
                        if ($brand->products()->count() > 0) {
                            $errors[] = "Cannot delete '{$brand->name}' because it has products.";
                            continue 2;
                        }

                        // Delete images
                        $this->deleteImageIfExists('brands/' . $brand->logo);
                        $this->deleteImageIfExists('brands/banners/' . $brand->banner);

                        // Delete analytics using relationship
                        $brand->dailyAnalytics()->delete();

                        // Detach categories using relationship
                        $brand->categories()->detach();

                        $brand->delete();
                        $count++;
                        $processedBrands[] = $brand->name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$brand->name}': " . $e->getMessage();
            }
        }

        // Log bulk action
        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action,
                'brand',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_brands' => $processedBrands,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} brands: " . implode(', ', $processedBrands)
            );
        }

        $message = "{$count} brands processed successfully.";
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
     * Export brands to CSV
     */
    public function export()
    {
        $brands = Brand::with(['dailyAnalytics'])->orderBy('name')->get();

        // Add analytics data
        foreach ($brands as $brand) {
            $brand->total_views = $brand->dailyAnalytics->sum('view_count');
            $brand->total_orders = $brand->dailyAnalytics->sum('order_count');
            $brand->total_revenue = $brand->dailyAnalytics->sum('total_revenue');
            $brand->products_count = $brand->products()->count();
        }

        $filename = 'brands-export-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($brands) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Name', 'Code', 'Slug', 'Products', 'Views', 'Orders', 'Revenue', 'Status', 'Featured', 'Created At']);

            // Add data
            foreach ($brands as $brand) {
                fputcsv($file, [
                    $brand->id,
                    $brand->name,
                    $brand->code,
                    $brand->slug,
                    $brand->products_count,
                    $brand->total_views,
                    $brand->total_orders,
                    $brand->total_revenue,
                    $brand->status ? 'Active' : 'Inactive',
                    $brand->is_featured ? 'Yes' : 'No',
                    $brand->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        $fields = ['name', 'code', 'order', 'status', 'is_featured', 'description'];

        foreach ($fields as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] != $newData[$field]) {
                $oldValue = is_bool($oldData[$field]) ? ($oldData[$field] ? 'Yes' : 'No') : $oldData[$field];
                $newValue = is_bool($newData[$field]) ? ($newData[$field] ? 'Yes' : 'No') : $newData[$field];
                $changes[] = $field . " changed from '{$oldValue}' to '{$newValue}'";
            }
        }

        return $changes;
    }

    // ==================== BRAND REQUEST METHODS ====================

    /**
     * Display pending brand requests from vendors
     */
    public function pendingRequests(Request $request)
    {
        $query = BrandRequest::with('vendor');

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
                    ->orWhere('requested_code', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total_pending' => BrandRequest::where('status', 'pending')->count(),
            'total_approved' => BrandRequest::where('status', 'approved')->count(),
            'total_rejected' => BrandRequest::where('status', 'rejected')->count(),
            'total_requests' => BrandRequest::count(),
        ];

        return view('admin.pages.brands.requests', compact('requests', 'statistics', 'status'));
    }

    /**
     * View single brand request details
     */
    public function viewRequest($id)
    {
        $brandRequest = BrandRequest::with('vendor', 'approvedBy', 'createdBrand')->findOrFail($id);
        return view('admin.pages.brands.request-details', compact('brandRequest'));
    }

    /**
     * Approve a brand request and create the brand
     */
    public function approveRequest(Request $request, $id)
    {
        $brandRequest = BrandRequest::with('vendor')->findOrFail($id);

        if ($brandRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        // Check if brand already exists
        $existingBrand = Brand::where('name', $brandRequest->requested_name)->first();
        if ($existingBrand) {
            return response()->json([
                'success' => false,
                'message' => 'A brand with name "' . $brandRequest->requested_name . '" already exists.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create the brand
            $brand = Brand::create([
                'name' => $brandRequest->requested_name,
                'slug' => Str::slug($brandRequest->requested_name),
                'code' => $brandRequest->requested_code ?? strtoupper(Str::slug($brandRequest->requested_name, '')),
                'description' => $brandRequest->description,
                'logo' => $brandRequest->logo,
                'status' => true,
                'order' => Brand::max('order') + 1,
            ]);

            // Attach categories to the brand (if any)
            if (!empty($brandRequest->requested_category_ids)) {
                $brand->categories()->sync($brandRequest->requested_category_ids);
            }

            // Update the request
            $brandRequest->update([
                'status' => 'approved',
                'approved_by' => auth('admin')->id(),
                'approved_at' => now(),
                'created_brand_id' => $brand->id,
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            $this->logActivity(
                'approve_brand_request',
                'brand_request',
                'admin',
                $brandRequest->id,
                $brandRequest->requested_name,
                null,
                [
                    'request_id' => $brandRequest->id,
                    'brand_id' => $brand->id,
                    'attached_categories' => $brandRequest->requested_category_ids
                ],
                "Approved brand request '{$brandRequest->requested_name}' with " . count($brandRequest->requested_category_ids ?? []) . " categories"
            );

            return response()->json([
                'success' => true,
                'message' => 'Brand request approved and brand created successfully.',
                'brand' => $brand
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a brand request
     */
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $brandRequest = BrandRequest::findOrFail($id);

        if ($brandRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $brandRequest->update([
            'status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
        ]);

        $this->logActivity(
            'reject_brand_request',
            'brand_request',
            'admin',
            $brandRequest->id,
            $brandRequest->requested_name,
            null,
            ['rejection_reason' => $request->rejection_reason],
            "Rejected brand request '{$brandRequest->requested_name}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Brand request rejected successfully.'
        ]);
    }

    /**
     * Delete a brand request
     */
    public function deleteRequest($id)
    {
        $brandRequest = BrandRequest::findOrFail($id);

        if ($brandRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved requests.'
            ], 422);
        }

        $requestName = $brandRequest->requested_name;

        if ($brandRequest->logo) {
            $this->deleteImageIfExists('brand-requests/' . $brandRequest->logo);
        }

        $brandRequest->delete();

        $this->logActivity(
            'delete_brand_request',
            'brand_request',
            'admin',
            $id,
            $requestName,
            null,
            null,
            "Deleted brand request '{$requestName}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Brand request deleted successfully.'
        ]);
    }

    /**
     * Bulk action on brand requests
     */
    public function bulkRequestAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'request_ids' => 'required|string',
        ]);

        $action = $request->action;
        $requestIds = json_decode($request->request_ids);
        $requests = BrandRequest::whereIn('id', $requestIds)->get();

        $count = 0;
        $errors = [];
        $processedRequests = [];

        foreach ($requests as $brandRequest) {
            try {
                if ($brandRequest->status !== 'pending' && in_array($action, ['approve', 'reject'])) {
                    $errors[] = "Request '{$brandRequest->requested_name}' is already processed.";
                    continue;
                }

                switch ($action) {
                    case 'approve':
                        // Check if brand already exists
                        $existingBrand = Brand::where('name', $brandRequest->requested_name)->first();
                        if ($existingBrand) {
                            $errors[] = "Brand '{$brandRequest->requested_name}' already exists.";
                            continue 2;
                        }

                        // Create the brand
                        $brand = Brand::create([
                            'name' => $brandRequest->requested_name,
                            'slug' => Str::slug($brandRequest->requested_name),
                            'code' => $brandRequest->requested_code ?? strtoupper(Str::slug($brandRequest->requested_name, '')),
                            'description' => $brandRequest->description,
                            'logo' => $brandRequest->logo,
                            'status' => true,
                            'order' => Brand::max('order') + 1,
                        ]);

                        // Attach categories to the brand
                        if (!empty($brandRequest->requested_category_ids)) {
                            $brand->categories()->sync($brandRequest->requested_category_ids);
                        }

                        // Update the request
                        $brandRequest->update([
                            'status' => 'approved',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'created_brand_id' => $brand->id,
                        ]);
                        $count++;
                        $processedRequests[] = $brandRequest->requested_name;
                        break;

                    case 'reject':
                        $brandRequest->update([
                            'status' => 'rejected',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'rejection_reason' => $request->rejection_reason ?? 'Bulk rejection',
                        ]);
                        $count++;
                        $processedRequests[] = $brandRequest->requested_name;
                        break;

                    case 'delete':
                        if ($brandRequest->status === 'approved') {
                            $errors[] = "Cannot delete approved request '{$brandRequest->requested_name}'.";
                            continue 2;
                        }

                        if ($brandRequest->logo) {
                            $this->deleteImageIfExists('brand-requests/' . $brandRequest->logo);
                        }

                        $brandRequest->delete();
                        $count++;
                        $processedRequests[] = $brandRequest->requested_name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$brandRequest->requested_name}': " . $e->getMessage();
            }
        }

        // Log bulk action
        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action . '_requests',
                'brand_request',
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
                "Bulk {$action} performed on {$count} brand requests"
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

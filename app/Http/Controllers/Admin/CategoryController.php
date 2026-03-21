<?php
// app/Http/Controllers/Admin/CategoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class CategoryController extends Controller implements HasMiddleware
{
    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view categories', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create categories', only: ['create', 'store']),
            new Middleware('permission:edit categories', only: ['edit', 'update', 'toggleStatus', 'toggleMenu', 'bulkAction']),
            new Middleware('permission:delete categories', only: ['destroy']),
        ];
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

        // Filter by type (main or sub)
        if ($request->filled('type')) {
            if ($request->type === 'main') {
                $query->whereNull('parent_id');
            } elseif ($request->type === 'sub') {
                $query->whereNotNull('parent_id');
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'view_count':
                $query->orderBy('view_count', 'desc');
                break;
            case 'product_count':
                $query->orderBy('product_count', 'desc');
                break;
            case 'total_revenue':
                $query->orderBy('total_revenue', 'desc');
                break;
            default:
                $query->orderBy('order', 'asc');
        }

        $categories = $query->paginate(15);

        // Statistics
        $statistics = [
            'total' => Category::count(),
            'active' => Category::where('status', true)->count(),
            'featured' => Category::where('is_featured', true)->count(),
            'total_views' => Category::sum('view_count'),
        ];

        if ($request->ajax()) {
            $table = view('admin.categories.partials.categories-table', compact('categories'))->render();
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
     * Category Analytics Dashboard
     */
    public function analytics()
    {
        // Top categories by views
        $topViewsCategories = Category::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top categories by revenue
        $topRevenueCategories = Category::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Categories with most products
        $topProductCategories = Category::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // SEO Performance
        $seoPerformance = Category::where('status', true)
            ->take(20)
            ->get();

        // Statistics
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', true)->count();
        $inactiveCategories = $totalCategories - $activeCategories;
        $featuredCategories = Category::where('is_featured', true)->count();
        $popularCategories = Category::where('is_popular', true)->count();
        $totalViews = Category::sum('view_count');
        $totalProducts = Category::sum('product_count');
        $totalRevenue = Category::sum('total_revenue');

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Category::whereDate('created_at', now()->subDays($i))->count();
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
            'seoPerformance',
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
            'avgViewsPerCategory'
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
            }
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail_image')) {
            $compressed = $this->imageCompressor->compress($request->file('thumbnail_image'), 'categories/thumbnails', 150, 80);
            if ($compressed['success']) {
                $data['thumbnail_image'] = $compressed['filename'];
            }
        }

        // Handle banner
        if ($request->hasFile('banner_image')) {
            $compressed = $this->imageCompressor->compress($request->file('banner_image'), 'categories/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner_image'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);
        $category = Category::create($data);

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
        $category->load('parent', 'children');
        return view('admin.pages.categories.show', compact('category'));
    }

    /**
     * Show form for editing category.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->orderBy('order')->get();
        return view('admin.pages.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update category.
     */
    public function update(Request $request, Category $category)
    {
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
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('categories/' . $category->image);
            $data['image'] = null;
        }

        // Handle thumbnail
        if ($request->hasFile('thumbnail_image')) {
            $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
            $compressed = $this->imageCompressor->compress($request->file('thumbnail_image'), 'categories/thumbnails', 150, 80);
            if ($compressed['success']) {
                $data['thumbnail_image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_thumbnail') && $request->remove_thumbnail) {
            $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
            $data['thumbnail_image'] = null;
        }

        // Handle banner
        if ($request->hasFile('banner_image')) {
            $this->deleteImageIfExists('categories/banners/' . $category->banner_image);
            $compressed = $this->imageCompressor->compress($request->file('banner_image'), 'categories/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner_image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_banner') && $request->remove_banner) {
            $this->deleteImageIfExists('categories/banners/' . $category->banner_image);
            $data['banner_image'] = null;
        }

        $category->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.'
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
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

        // Delete images
        $this->deleteImageIfExists('categories/' . $category->image);
        $this->deleteImageIfExists('categories/thumbnails/' . $category->thumbnail_image);
        $this->deleteImageIfExists('categories/banners/' . $category->banner_image);

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['status' => !$category->status]);
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
        $category->update(['show_in_menu' => !$category->show_in_menu]);
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
        $category->update(['is_featured' => !$category->is_featured]);
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
        $category->update(['is_popular' => !$category->is_popular]);
        return response()->json([
            'success' => true,
            'message' => 'Popular status updated.',
            'is_popular' => $category->is_popular
        ]);
    }

    /**
     * Bulk action on categories - Supports all 7 actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,popular,unpopular',
            'category_ids' => 'required|string',
        ]);

        $action = $request->action;
        $categoryIds = json_decode($request->category_ids);

        // Check permissions based on action
        if (in_array($action, ['activate', 'deactivate', 'feature', 'unfeature', 'popular', 'unpopular'])) {
            if (!auth('admin')->user()->can('edit categories')) {
                return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
            }
        }

        if ($action === 'delete') {
            if (!auth('admin')->user()->can('delete categories')) {
                return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
            }
        }

        $categories = Category::whereIn('id', $categoryIds)->get();
        $count = 0;
        $errors = [];

        foreach ($categories as $category) {
            try {
                switch ($action) {
                    case 'activate':
                        $category->update(['status' => true]);
                        $count++;
                        break;

                    case 'deactivate':
                        $category->update(['status' => false]);
                        $count++;
                        break;

                    case 'feature':
                        $category->update(['is_featured' => true]);
                        $count++;
                        break;

                    case 'unfeature':
                        $category->update(['is_featured' => false]);
                        $count++;
                        break;

                    case 'popular':
                        $category->update(['is_popular' => true]);
                        $count++;
                        break;

                    case 'unpopular':
                        $category->update(['is_popular' => false]);
                        $count++;
                        break;

                    case 'delete':
                        // Check if category has children or products
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

                        $category->delete();
                        $count++;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$category->name}': " . $e->getMessage();
            }
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
     * Delete image if exists.
     */
    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

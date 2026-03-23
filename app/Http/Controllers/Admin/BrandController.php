<?php
// app/Http/Controllers/Admin/BrandController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class BrandController extends Controller implements HasMiddleware
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
            new Middleware('permission:view brands', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create brands', only: ['create', 'store']),
            new Middleware('permission:edit brands', only: ['edit', 'update']),
            new Middleware('permission:delete brands', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'code':
                $query->orderBy('code', $sortOrder);
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
            case 'avg_rating':
                $query->orderBy('avg_rating', 'desc');
                break;
            default:
                $query->orderBy('order', 'asc');
        }

        $brands = $query->paginate(15);

        $statistics = [
            'total' => Brand::count(),
            'active' => Brand::where('status', true)->count(),
            'featured' => Brand::where('is_featured', true)->count(),
            'total_views' => Brand::sum('view_count'),
            'total_products' => Brand::sum('product_count'),
            'total_revenue' => Brand::sum('total_revenue'),
            'avg_rating' => Brand::avg('avg_rating'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.brands.partials.brands-table', compact('brands'))->render();
            $pagination = $brands->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.brands.index', compact('brands', 'statistics'));
    }

    public function analytics()
    {
        // Top brands by views
        $topViewsBrands = Brand::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top brands by revenue
        $topRevenueBrands = Brand::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Top brands by products
        $topProductBrands = Brand::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Top rated brands
        $topRatedBrands = Brand::where('status', true)
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        // Featured brands
        $featuredBrands = Brand::where('is_featured', true)->count();

        // Statistics
        $totalBrands = Brand::count();
        $activeBrands = Brand::where('status', true)->count();
        $inactiveBrands = $totalBrands - $activeBrands;
        $totalViews = Brand::sum('view_count');
        $totalProducts = Brand::sum('product_count');
        $totalRevenue = Brand::sum('total_revenue');
        $totalOrders = Brand::sum('order_count');
        $avgRating = Brand::avg('avg_rating');

        // Averages
        $avgProductsPerBrand = $totalBrands > 0 ? round($totalProducts / $totalBrands, 1) : 0;
        $avgViewsPerBrand = $totalBrands > 0 ? round($totalViews / $totalBrands, 0) : 0;
        $avgRevenuePerBrand = $totalBrands > 0 ? round($totalRevenue / $totalBrands, 2) : 0;

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Brand::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.brands.analytics', compact(
            'topViewsBrands',
            'topRevenueBrands',
            'topProductBrands',
            'topRatedBrands',
            'featuredBrands',
            'totalBrands',
            'activeBrands',
            'inactiveBrands',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'avgRating',
            'avgProductsPerBrand',
            'avgViewsPerBrand',
            'avgRevenuePerBrand',
            'growthData',
            'growthLabels'
        ));
    }

    public function create()
    {
        return view('admin.pages.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'code' => 'required|string|max:50|unique:brands,code',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['logo', 'banner']);

        // Handle logo
        if ($request->hasFile('logo')) {
            $compressed = $this->imageCompressor->compress($request->file('logo'), 'brands/logos', 150, 85);
            if ($compressed['success']) {
                $data['logo'] = $compressed['filename'];
            }
        }

        // Handle banner
        if ($request->hasFile('banner')) {
            $compressed = $this->imageCompressor->compress($request->file('banner'), 'brands/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);
        $brand = Brand::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand created successfully.',
                'brand' => $brand
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand)
    {
        $brand->incrementViewCount();
        return view('admin.pages.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.pages.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'code' => 'required|string|max:50|unique:brands,code,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'website' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['logo', 'banner', 'remove_logo', 'remove_banner']);

        // Handle logo
        if ($request->hasFile('logo')) {
            $this->deleteImageIfExists('brands/logos/' . $brand->logo);
            $compressed = $this->imageCompressor->compress($request->file('logo'), 'brands/logos', 150, 85);
            if ($compressed['success']) {
                $data['logo'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_logo') && $request->remove_logo) {
            $this->deleteImageIfExists('brands/logos/' . $brand->logo);
            $data['logo'] = null;
        }

        // Handle banner
        if ($request->hasFile('banner')) {
            $this->deleteImageIfExists('brands/banners/' . $brand->banner);
            $compressed = $this->imageCompressor->compress($request->file('banner'), 'brands/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_banner') && $request->remove_banner) {
            $this->deleteImageIfExists('brands/banners/' . $brand->banner);
            $data['banner'] = null;
        }

        $brand->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Brand updated successfully.'
            ]);
        }

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete brand because it has ' . $brand->products()->count() . ' products.'
            ], 422);
        }

        $this->deleteImageIfExists('brands/logos/' . $brand->logo);
        $this->deleteImageIfExists('brands/banners/' . $brand->banner);
        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.'
        ]);
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->update(['status' => !$brand->status]);
        return response()->json([
            'success' => true,
            'message' => 'Brand status updated.',
            'status' => $brand->status
        ]);
    }

    public function toggleFeatured(Brand $brand)
    {
        $brand->update(['is_featured' => !$brand->is_featured]);
        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $brand->is_featured
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'brand_ids' => 'required|string',
        ]);

        $action = $request->action;
        $brandIds = json_decode($request->brand_ids);

        if (in_array($action, ['delete']) && !auth('admin')->user()->can('delete brands')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $brands = Brand::whereIn('id', $brandIds)->get();
        $count = 0;

        foreach ($brands as $brand) {
            if ($action === 'activate') {
                $brand->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $brand->update(['status' => false]);
                $count++;
            } elseif ($action === 'feature') {
                $brand->update(['is_featured' => true]);
                $count++;
            } elseif ($action === 'unfeature') {
                $brand->update(['is_featured' => false]);
                $count++;
            } elseif ($action === 'delete') {
                if ($brand->products()->count() == 0) {
                    $this->deleteImageIfExists('brands/logos/' . $brand->logo);
                    $this->deleteImageIfExists('brands/banners/' . $brand->banner);
                    $brand->delete();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} brands {$action}d successfully."
        ]);
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

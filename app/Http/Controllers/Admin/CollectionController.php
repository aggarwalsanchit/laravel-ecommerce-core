<?php
// app/Http/Controllers/Admin/CollectionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class CollectionController extends Controller implements HasMiddleware
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
            new Middleware('permission:view collections', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create collections', only: ['create', 'store']),
            new Middleware('permission:edit collections', only: ['edit', 'update']),
            new Middleware('permission:delete collections', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Collection::query();

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

        $collections = $query->paginate(15);

        $statistics = [
            'total' => Collection::count(),
            'active' => Collection::where('status', true)->count(),
            'featured' => Collection::where('is_featured', true)->count(),
            'total_views' => Collection::sum('view_count'),
            'total_products' => Collection::sum('product_count'),
            'total_revenue' => Collection::sum('total_revenue'),
            'avg_rating' => Collection::avg('avg_rating'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.collections.partials.collections-table', compact('collections'))->render();
            $pagination = $collections->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.collections.index', compact('collections', 'statistics'));
    }

    public function analytics()
    {
        // Top collections by views
        $topViewsCollections = Collection::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top collections by revenue
        $topRevenueCollections = Collection::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Top collections by products
        $topProductCollections = Collection::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Top rated collections
        $topRatedCollections = Collection::where('status', true)
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        // Featured collections
        $featuredCollections = Collection::where('is_featured', true)->count();

        // Statistics
        $totalCollections = Collection::count();
        $activeCollections = Collection::where('status', true)->count();
        $inactiveCollections = $totalCollections - $activeCollections;
        $totalViews = Collection::sum('view_count');
        $totalProducts = Collection::sum('product_count');
        $totalRevenue = Collection::sum('total_revenue');
        $totalOrders = Collection::sum('order_count');
        $avgRating = Collection::avg('avg_rating');

        // Averages
        $avgProductsPerCollection = $totalCollections > 0 ? round($totalProducts / $totalCollections, 1) : 0;
        $avgViewsPerCollection = $totalCollections > 0 ? round($totalViews / $totalCollections, 0) : 0;
        $avgRevenuePerCollection = $totalCollections > 0 ? round($totalRevenue / $totalCollections, 2) : 0;

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Collection::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.collections.analytics', compact(
            'topViewsCollections',
            'topRevenueCollections',
            'topProductCollections',
            'topRatedCollections',
            'featuredCollections',
            'totalCollections',
            'activeCollections',
            'inactiveCollections',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'avgRating',
            'avgProductsPerCollection',
            'avgViewsPerCollection',
            'avgRevenuePerCollection',
            'growthData',
            'growthLabels'
        ));
    }

    public function create()
    {
        return view('admin.pages.collections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:collections,name',
            'code' => 'required|string|max:50|unique:collections,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'icon' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'banner']);

        // Handle main image
        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'collections', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        // Handle banner image
        if ($request->hasFile('banner')) {
            $compressed = $this->imageCompressor->compress($request->file('banner'), 'collections/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);
        $collection = Collection::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Collection created successfully.',
                'collection' => $collection
            ]);
        }

        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully.');
    }

    public function show(Collection $collection)
    {
        $collection->incrementViewCount();
        return view('admin.pages.collections.show', compact('collection'));
    }

    public function edit(Collection $collection)
    {
        return view('admin.pages.collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'code' => 'required|string|max:50|unique:collections,code,' . $collection->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
            'icon' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'banner', 'remove_image', 'remove_banner']);

        // Handle main image
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('collections/' . $collection->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'collections', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('collections/' . $collection->image);
            $data['image'] = null;
        }

        // Handle banner image
        if ($request->hasFile('banner')) {
            $this->deleteImageIfExists('collections/banners/' . $collection->banner);
            $compressed = $this->imageCompressor->compress($request->file('banner'), 'collections/banners', 1920, 90);
            if ($compressed['success']) {
                $data['banner'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_banner') && $request->remove_banner) {
            $this->deleteImageIfExists('collections/banners/' . $collection->banner);
            $data['banner'] = null;
        }

        $collection->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Collection updated successfully.'
            ]);
        }

        return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        if ($collection->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete collection because it has ' . $collection->products()->count() . ' products.'
            ], 422);
        }

        $this->deleteImageIfExists('collections/' . $collection->image);
        $this->deleteImageIfExists('collections/banners/' . $collection->banner);
        $collection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Collection deleted successfully.'
        ]);
    }

    public function toggleStatus(Collection $collection)
    {
        $collection->update(['status' => !$collection->status]);
        return response()->json([
            'success' => true,
            'message' => 'Collection status updated.',
            'status' => $collection->status
        ]);
    }

    public function toggleFeatured(Collection $collection)
    {
        $collection->update(['is_featured' => !$collection->is_featured]);
        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $collection->is_featured
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'collection_ids' => 'required|string',
        ]);

        $action = $request->action;
        $collectionIds = json_decode($request->collection_ids);

        if (in_array($action, ['delete']) && !auth('admin')->user()->can('delete collections')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $collections = Collection::whereIn('id', $collectionIds)->get();
        $count = 0;

        foreach ($collections as $collection) {
            if ($action === 'activate') {
                $collection->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $collection->update(['status' => false]);
                $count++;
            } elseif ($action === 'feature') {
                $collection->update(['is_featured' => true]);
                $count++;
            } elseif ($action === 'unfeature') {
                $collection->update(['is_featured' => false]);
                $count++;
            } elseif ($action === 'delete') {
                if ($collection->products()->count() == 0) {
                    $this->deleteImageIfExists('collections/' . $collection->image);
                    $this->deleteImageIfExists('collections/banners/' . $collection->banner);
                    $collection->delete();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} collections {$action}d successfully."
        ]);
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

<?php
// app/Http/Controllers/Admin/FabricController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fabric;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class FabricController extends Controller implements HasMiddleware
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
            new Middleware('permission:view fabrics', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create fabrics', only: ['create', 'store']),
            new Middleware('permission:edit fabrics', only: ['edit', 'update']),
            new Middleware('permission:delete fabrics', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Fabric::query();

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
            default:
                $query->orderBy('order', 'asc');
        }

        $fabrics = $query->paginate(15);

        $statistics = [
            'total' => Fabric::count(),
            'active' => Fabric::where('status', true)->count(),
            'total_views' => Fabric::sum('view_count'),
            'total_products' => Fabric::sum('product_count'),
            'total_revenue' => Fabric::sum('total_revenue'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.fabrics.partials.fabrics-table', compact('fabrics'))->render();
            $pagination = $fabrics->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.fabrics.index', compact('fabrics', 'statistics'));
    }

    public function analytics()
    {
        // Top fabrics by views
        $topViewsFabrics = Fabric::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top fabrics by revenue
        $topRevenueFabrics = Fabric::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Fabrics with most products
        $topProductFabrics = Fabric::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Top rated fabrics
        $topRatedFabrics = Fabric::where('status', true)
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $totalFabrics = Fabric::count();
        $activeFabrics = Fabric::where('status', true)->count();
        $inactiveFabrics = $totalFabrics - $activeFabrics;
        $totalViews = Fabric::sum('view_count');
        $totalProducts = Fabric::sum('product_count');
        $totalRevenue = Fabric::sum('total_revenue');
        $totalOrders = Fabric::sum('order_count');
        $avgRating = Fabric::avg('avg_rating');

        // Averages
        $avgProductsPerFabric = $totalFabrics > 0 ? round($totalProducts / $totalFabrics, 1) : 0;
        $avgViewsPerFabric = $totalFabrics > 0 ? round($totalViews / $totalFabrics, 0) : 0;
        $avgRevenuePerFabric = $totalFabrics > 0 ? round($totalRevenue / $totalFabrics, 2) : 0;
        $conversionRate = $totalViews > 0 ? round(($totalOrders / $totalViews) * 100, 1) : 0;

        // Care instructions statistics
        $washingStats = Fabric::whereNotNull('washing')->select('washing', \DB::raw('count(*) as count'))->groupBy('washing')->pluck('count', 'washing')->toArray();
        $ironingStats = Fabric::whereNotNull('ironing')->select('ironing', \DB::raw('count(*) as count'))->groupBy('ironing')->pluck('count', 'ironing')->toArray();
        $dryingStats = Fabric::whereNotNull('drying')->select('drying', \DB::raw('count(*) as count'))->groupBy('drying')->pluck('count', 'drying')->toArray();
        $bleachingStats = Fabric::whereNotNull('bleaching')->select('bleaching', \DB::raw('count(*) as count'))->groupBy('bleaching')->pluck('count', 'bleaching')->toArray();

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Fabric::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.fabrics.analytics', compact(
            'topViewsFabrics',
            'topRevenueFabrics',
            'topProductFabrics',
            'topRatedFabrics',
            'totalFabrics',
            'activeFabrics',
            'inactiveFabrics',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'avgRating',
            'avgProductsPerFabric',
            'avgViewsPerFabric',
            'avgRevenuePerFabric',
            'conversionRate',
            'washingStats',
            'ironingStats',
            'dryingStats',
            'bleachingStats',
            'growthData',
            'growthLabels'
        ));
    }

    public function create()
    {
        return view('admin.pages.fabrics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:fabrics,name',
            'code' => 'required|string|max:50|unique:fabrics,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'fabrics', 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);
        $fabric = Fabric::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fabric created successfully.',
                'fabric' => $fabric
            ]);
        }

        return redirect()->route('admin.fabrics.index')->with('success', 'Fabric created successfully.');
    }

    public function show(Fabric $fabric)
    {
        $fabric->incrementViewCount();
        return view('admin.pages.fabrics.show', compact('fabric'));
    }

    public function edit(Fabric $fabric)
    {
        return view('admin.pages.fabrics.edit', compact('fabric'));
    }

    public function update(Request $request, Fabric $fabric)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:fabrics,name,' . $fabric->id,
            'code' => 'required|string|max:50|unique:fabrics,code,' . $fabric->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'remove_image']);

        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('fabrics/' . $fabric->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'fabrics', 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('fabrics/' . $fabric->image);
            $data['image'] = null;
        }

        $fabric->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Fabric updated successfully.'
            ]);
        }

        return redirect()->route('admin.fabrics.index')->with('success', 'Fabric updated successfully.');
    }

    public function destroy(Fabric $fabric)
    {
        if ($fabric->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete fabric because it has ' . $fabric->products()->count() . ' products.'
            ], 422);
        }

        $this->deleteImageIfExists('fabrics/' . $fabric->image);
        $fabric->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fabric deleted successfully.'
        ]);
    }

    public function toggleStatus(Fabric $fabric)
    {
        $fabric->update(['status' => !$fabric->status]);
        return response()->json([
            'success' => true,
            'message' => 'Fabric status updated.',
            'status' => $fabric->status
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'fabric_ids' => 'required|string',
        ]);

        $action = $request->action;
        $fabricIds = json_decode($request->fabric_ids);

        if ($action === 'delete' && !auth('admin')->user()->can('delete fabrics')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $fabrics = Fabric::whereIn('id', $fabricIds)->get();
        $count = 0;

        foreach ($fabrics as $fabric) {
            if ($action === 'activate') {
                $fabric->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $fabric->update(['status' => false]);
                $count++;
            } elseif ($action === 'delete') {
                if ($fabric->products()->count() == 0) {
                    $this->deleteImageIfExists('fabrics/' . $fabric->image);
                    $fabric->delete();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} fabrics {$action}d successfully."
        ]);
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

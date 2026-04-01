<?php
// app/Http/Controllers/Admin/OccasionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occasion;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class OccasionController extends Controller implements HasMiddleware
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
            new Middleware('permission:view occasions', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create occasions', only: ['create', 'store']),
            new Middleware('permission:edit occasions', only: ['edit', 'update']),
            new Middleware('permission:delete occasions', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Occasion::query();

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

        $occasions = $query->paginate(15);

        $statistics = [
            'total' => Occasion::count(),
            'active' => Occasion::where('status', true)->count(),
            'featured' => Occasion::where('is_featured', true)->count(),
            'total_views' => Occasion::sum('view_count'),
            'total_products' => Occasion::sum('product_count'),
            'total_revenue' => Occasion::sum('total_revenue'),
            'avg_rating' => Occasion::avg('avg_rating'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.occasions.partials.occasions-table', compact('occasions'))->render();
            $pagination = $occasions->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.occasions.index', compact('occasions', 'statistics'));
    }

    public function analytics()
    {
        // Top occasions by views
        $topViewsOccasions = Occasion::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top occasions by revenue
        $topRevenueOccasions = Occasion::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Top occasions by products
        $topProductOccasions = Occasion::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Top rated occasions
        $topRatedOccasions = Occasion::where('status', true)
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        // Featured occasions
        $featuredOccasions = Occasion::where('is_featured', true)->count();

        // Statistics
        $totalOccasions = Occasion::count();
        $activeOccasions = Occasion::where('status', true)->count();
        $inactiveOccasions = $totalOccasions - $activeOccasions;
        $totalViews = Occasion::sum('view_count');
        $totalProducts = Occasion::sum('product_count');
        $totalRevenue = Occasion::sum('total_revenue');
        $totalOrders = Occasion::sum('order_count');
        $avgRating = Occasion::avg('avg_rating');

        // Averages
        $avgProductsPerOccasion = $totalOccasions > 0 ? round($totalProducts / $totalOccasions, 1) : 0;
        $avgViewsPerOccasion = $totalOccasions > 0 ? round($totalViews / $totalOccasions, 0) : 0;
        $avgRevenuePerOccasion = $totalOccasions > 0 ? round($totalRevenue / $totalOccasions, 2) : 0;

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Occasion::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.occasions.analytics', compact(
            'topViewsOccasions',
            'topRevenueOccasions',
            'topProductOccasions',
            'topRatedOccasions',
            'featuredOccasions',
            'totalOccasions',
            'activeOccasions',
            'inactiveOccasions',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'avgRating',
            'avgProductsPerOccasion',
            'avgViewsPerOccasion',
            'avgRevenuePerOccasion',
            'growthData',
            'growthLabels'
        ));
    }

    public function create()
    {
        return view('admin.pages.occasions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:occasions,name',
            'code' => 'required|string|max:50|unique:occasions,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image']);

        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'occasions', 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);
        $occasion = Occasion::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Occasion created successfully.',
                'occasion' => $occasion
            ]);
        }

        return redirect()->route('admin.occasions.index')->with('success', 'Occasion created successfully.');
    }

    public function show(Occasion $occasion)
    {
        $occasion->incrementViewCount();
        return view('admin.pages.occasions.show', compact('occasion'));
    }

    public function edit(Occasion $occasion)
    {
        return view('admin.pages.occasions.edit', compact('occasion'));
    }

    public function update(Request $request, Occasion $occasion)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:occasions,name,' . $occasion->id,
            'code' => 'required|string|max:50|unique:occasions,code,' . $occasion->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'remove_image']);

        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('occasions/' . $occasion->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'occasions', 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('occasions/' . $occasion->image);
            $data['image'] = null;
        }

        $occasion->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Occasion updated successfully.'
            ]);
        }

        return redirect()->route('admin.occasions.index')->with('success', 'Occasion updated successfully.');
    }

    public function destroy(Occasion $occasion)
    {
        if ($occasion->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete occasion because it has ' . $occasion->products()->count() . ' products.'
            ], 422);
        }

        $this->deleteImageIfExists('occasions/' . $occasion->image);
        $occasion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Occasion deleted successfully.'
        ]);
    }

    public function toggleStatus(Occasion $occasion)
    {
        $occasion->update(['status' => !$occasion->status]);
        return response()->json([
            'success' => true,
            'message' => 'Occasion status updated.',
            'status' => $occasion->status
        ]);
    }

    public function toggleFeatured(Occasion $occasion)
    {
        $occasion->update(['is_featured' => !$occasion->is_featured]);
        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $occasion->is_featured
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'occasion_ids' => 'required|string',
        ]);

        $action = $request->action;
        $occasionIds = json_decode($request->occasion_ids);

        if (in_array($action, ['delete']) && !auth('admin')->user()->can('delete occasions')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $occasions = Occasion::whereIn('id', $occasionIds)->get();
        $count = 0;

        foreach ($occasions as $occasion) {
            if ($action === 'activate') {
                $occasion->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $occasion->update(['status' => false]);
                $count++;
            } elseif ($action === 'feature') {
                $occasion->update(['is_featured' => true]);
                $count++;
            } elseif ($action === 'unfeature') {
                $occasion->update(['is_featured' => false]);
                $count++;
            } elseif ($action === 'delete') {
                if ($occasion->products()->count() == 0) {
                    $this->deleteImageIfExists('occasions/' . $occasion->image);
                    $occasion->delete();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} occasions {$action}d successfully."
        ]);
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

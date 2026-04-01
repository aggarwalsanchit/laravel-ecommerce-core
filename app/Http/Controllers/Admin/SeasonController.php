<?php
// app/Http/Controllers/Admin/SeasonController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class SeasonController extends Controller implements HasMiddleware
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
            new Middleware('permission:view seasons', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create seasons', only: ['create', 'store']),
            new Middleware('permission:edit seasons', only: ['edit', 'update']),
            new Middleware('permission:delete seasons', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Season::query();

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

        if ($request->filled('current')) {
            if ($request->current === 'yes') {
                $query->where('is_current', true);
            } elseif ($request->current === 'no') {
                $query->where('is_current', false);
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

        $seasons = $query->paginate(15);

        $statistics = [
            'total' => Season::count(),
            'active' => Season::where('status', true)->count(),
            'current' => Season::where('is_current', true)->count(),
            'total_views' => Season::sum('view_count'),
            'total_products' => Season::sum('product_count'),
            'total_revenue' => Season::sum('total_revenue'),
            'avg_rating' => Season::avg('avg_rating'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.seasons.partials.seasons-table', compact('seasons'))->render();
            $pagination = $seasons->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.seasons.index', compact('seasons', 'statistics'));
    }

    public function analytics()
    {
        // Top seasons by views
        $topViewsSeasons = Season::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top seasons by revenue
        $topRevenueSeasons = Season::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Top seasons by products
        $topProductSeasons = Season::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Top rated seasons
        $topRatedSeasons = Season::where('status', true)
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->take(10)
            ->get();

        // Current season
        $currentSeason = Season::where('is_current', true)->first();

        // Statistics
        $totalSeasons = Season::count();
        $activeSeasons = Season::where('status', true)->count();
        $inactiveSeasons = $totalSeasons - $activeSeasons;
        $currentSeasons = Season::where('is_current', true)->count();
        $totalViews = Season::sum('view_count');
        $totalProducts = Season::sum('product_count');
        $totalRevenue = Season::sum('total_revenue');
        $totalOrders = Season::sum('order_count');
        $avgRating = Season::avg('avg_rating');

        // Averages
        $avgProductsPerSeason = $totalSeasons > 0 ? round($totalProducts / $totalSeasons, 1) : 0;
        $avgViewsPerSeason = $totalSeasons > 0 ? round($totalViews / $totalSeasons, 0) : 0;
        $avgRevenuePerSeason = $totalSeasons > 0 ? round($totalRevenue / $totalSeasons, 2) : 0;

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Season::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.seasons.analytics', compact(
            'topViewsSeasons',
            'topRevenueSeasons',
            'topProductSeasons',
            'topRatedSeasons',
            'currentSeason',
            'totalSeasons',
            'activeSeasons',
            'inactiveSeasons',
            'currentSeasons',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'avgRating',
            'avgProductsPerSeason',
            'avgViewsPerSeason',
            'avgRevenuePerSeason',
            'growthData',
            'growthLabels'
        ));
    }

    public function create()
    {
        return view('admin.pages.seasons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:seasons,name',
            'code' => 'required|string|max:50|unique:seasons,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_current' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image']);

        // Handle image
        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'seasons', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);

        // If setting as current, remove current from others
        if ($request->has('is_current') && $request->is_current) {
            Season::where('is_current', true)->update(['is_current' => false]);
        }

        $season = Season::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Season created successfully.',
                'season' => $season
            ]);
        }

        return redirect()->route('admin.seasons.index')->with('success', 'Season created successfully.');
    }

    public function show(Season $season)
    {
        $season->incrementViewCount();
        return view('admin.pages.seasons.show', compact('season'));
    }

    public function edit(Season $season)
    {
        return view('admin.pages.seasons.edit', compact('season'));
    }

    public function update(Request $request, Season $season)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:seasons,name,' . $season->id,
            'code' => 'required|string|max:50|unique:seasons,code,' . $season->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_current' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'remove_image']);

        // Handle image
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('seasons/' . $season->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'seasons', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('seasons/' . $season->image);
            $data['image'] = null;
        }

        // If setting as current, remove current from others
        if ($request->has('is_current') && $request->is_current && !$season->is_current) {
            Season::where('is_current', true)->update(['is_current' => false]);
        }

        $season->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Season updated successfully.'
            ]);
        }

        return redirect()->route('admin.seasons.index')->with('success', 'Season updated successfully.');
    }

    public function destroy(Season $season)
    {
        if ($season->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete season because it has ' . $season->products()->count() . ' products.'
            ], 422);
        }

        $this->deleteImageIfExists('seasons/' . $season->image);
        $season->delete();

        return response()->json([
            'success' => true,
            'message' => 'Season deleted successfully.'
        ]);
    }

    public function toggleStatus(Season $season)
    {
        $season->update(['status' => !$season->status]);
        return response()->json([
            'success' => true,
            'message' => 'Season status updated.',
            'status' => $season->status
        ]);
    }

    public function setCurrent(Season $season)
    {
        Season::where('is_current', true)->update(['is_current' => false]);
        $season->update(['is_current' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Current season updated successfully.',
            'is_current' => true
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,set_current',
            'season_ids' => 'required|string',
        ]);

        $action = $request->action;
        $seasonIds = json_decode($request->season_ids);

        if (in_array($action, ['delete']) && !auth('admin')->user()->can('delete seasons')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $seasons = Season::whereIn('id', $seasonIds)->get();
        $count = 0;

        foreach ($seasons as $season) {
            if ($action === 'activate') {
                $season->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $season->update(['status' => false]);
                $count++;
            } elseif ($action === 'set_current') {
                Season::where('is_current', true)->update(['is_current' => false]);
                $season->update(['is_current' => true]);
                $count++;
            } elseif ($action === 'delete') {
                if ($season->products()->count() == 0) {
                    $this->deleteImageIfExists('seasons/' . $season->image);
                    $season->delete();
                    $count++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} seasons {$action}d successfully."
        ]);
    }

    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

<?php
// app/Http/Controllers/Admin/ColorController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class ColorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view colors', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create colors', only: ['create', 'store']),
            new Middleware('permission:edit colors', only: ['edit', 'update']),
            new Middleware('permission:delete colors', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of colors.
     */
    public function index(Request $request)
    {
        $query = Color::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('hex_code', 'like', "%{$search}%");
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
            case 'hex_code':
                $query->orderBy('hex_code', $sortOrder);
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

        $colors = $query->paginate(15);

        // Statistics
        $statistics = [
            'total' => Color::count(),
            'active' => Color::where('status', true)->count(),
            'total_views' => Color::sum('view_count'),
            'total_products' => Color::sum('product_count'),
            'total_revenue' => Color::sum('total_revenue'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.colors.partials.colors-table', compact('colors'))->render();
            $pagination = $colors->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.colors.index', compact('colors', 'statistics'));
    }

    /**
     * Color Analytics Dashboard
     */
    public function analytics()
    {
        // Top colors by views
        $topViewsColors = Color::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top colors by revenue
        $topRevenueColors = Color::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Colors with most products
        $topProductColors = Color::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $totalColors = Color::count();
        $activeColors = Color::where('status', true)->count();
        $inactiveColors = $totalColors - $activeColors;
        $totalViews = Color::sum('view_count');
        $totalProducts = Color::sum('product_count');
        $totalRevenue = Color::sum('total_revenue');
        $totalOrders = Color::sum('order_count');

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Color::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.colors.analytics', compact(
            'topViewsColors',
            'topRevenueColors',
            'topProductColors',
            'totalColors',
            'activeColors',
            'inactiveColors',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'growthData',
            'growthLabels'
        ));
    }

    /**
     * Show form for creating new color.
     */
    public function create()
    {
        return view('admin.pages.colors.create');
    }

    /**
     * Store newly created color.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'code' => 'required|string|max:50|unique:colors,code',
            'hex_code' => 'required|string|max:7|unique:colors,hex_code|regex:/^#[a-f0-9]{6}$/i',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $color = Color::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Color created successfully.',
                'color' => $color
            ]);
        }

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    /**
     * Display color details.
     */
    public function show(Color $color)
    {
        $color->incrementViewCount();
        return view('admin.pages.colors.show', compact('color'));
    }

    /**
     * Show form for editing color.
     */
    public function edit(Color $color)
    {
        return view('admin.pages.colors.edit', compact('color'));
    }

    /**
     * Update color.
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'code' => 'required|string|max:50|unique:colors,code,' . $color->id,
            'hex_code' => 'required|string|max:7|unique:colors,hex_code,' . $color->id . '|regex:/^#[a-f0-9]{6}$/i',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $color->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Color updated successfully.'
            ]);
        }

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    /**
     * Delete color.
     */
    public function destroy(Color $color)
    {
        if ($color->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete color because it has ' . $color->products()->count() . ' products.'
            ], 422);
        }

        $color->delete();

        return response()->json([
            'success' => true,
            'message' => 'Color deleted successfully.'
        ]);
    }

    /**
     * Toggle color status.
     */
    public function toggleStatus(Color $color)
    {
        $color->update(['status' => !$color->status]);
        return response()->json([
            'success' => true,
            'message' => 'Color status updated.',
            'status' => $color->status
        ]);
    }

    /**
     * Bulk action on colors.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'color_ids' => 'required|string',
        ]);

        $action = $request->action;
        $colorIds = json_decode($request->color_ids);

        if ($action === 'delete' && !auth('admin')->user()->can('delete colors')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        if (in_array($action, ['activate', 'deactivate']) && !auth('admin')->user()->can('edit colors')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $colors = Color::whereIn('id', $colorIds)->get();
        $count = 0;
        $errors = [];

        foreach ($colors as $color) {
            try {
                if ($action === 'activate') {
                    $color->update(['status' => true]);
                    $count++;
                } elseif ($action === 'deactivate') {
                    $color->update(['status' => false]);
                    $count++;
                } elseif ($action === 'delete') {
                    if ($color->products()->count() > 0) {
                        $errors[] = "Cannot delete '{$color->name}' because it has products.";
                        continue;
                    }
                    $color->delete();
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$color->name}': " . $e->getMessage();
            }
        }

        $message = "{$count} colors processed successfully.";
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

    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:colors,name',
            'code' => 'nullable|unique:colors,code',
            'hex_code' => 'nullable',
        ]);

        $color = Color::create([
            'name' => $request->name,
            'code' => $request->code,
            'slug' => Str::slug($request->name),
            'hex_code' => $request->hex_code ?? '#000000',
            'status' => $request->status ?? true,
        ]);

        // Get updated colors list
        $colors = Color::where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'color' => $color,
            'colors' => $colors,
            'message' => 'Color added successfully'
        ]);
    }
}

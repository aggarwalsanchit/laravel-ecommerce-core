<?php
// app/Http/Controllers/Admin/SizeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class SizeController extends Controller implements HasMiddleware
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
            new Middleware('permission:view sizes', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create sizes', only: ['create', 'store']),
            new Middleware('permission:edit sizes', only: ['edit', 'update']),
            new Middleware('permission:delete sizes', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of sizes.
     */
    public function index(Request $request)
    {
        $query = Size::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
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

        $sizes = $query->paginate(15);

        // Statistics
        $statistics = [
            'total' => Size::count(),
            'active' => Size::where('status', true)->count(),
            'total_views' => Size::sum('view_count'),
            'total_products' => Size::sum('product_count'),
            'total_revenue' => Size::sum('total_revenue'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.sizes.partials.sizes-table', compact('sizes'))->render();
            $pagination = $sizes->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.sizes.index', compact('sizes', 'statistics'));
    }

    /**
     * Size Analytics Dashboard
     */
    public function analytics()
    {
        // Top sizes by views
        $topViewsSizes = Size::where('status', true)
            ->orderBy('view_count', 'desc')
            ->take(10)
            ->get();

        // Top sizes by revenue
        $topRevenueSizes = Size::where('status', true)
            ->where('total_revenue', '>', 0)
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Sizes with most products
        $topProductSizes = Size::where('status', true)
            ->where('product_count', '>', 0)
            ->orderBy('product_count', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $totalSizes = Size::count();
        $activeSizes = Size::where('status', true)->count();
        $inactiveSizes = $totalSizes - $activeSizes;
        $totalViews = Size::sum('view_count');
        $totalProducts = Size::sum('product_count');
        $totalRevenue = Size::sum('total_revenue');
        $totalOrders = Size::sum('order_count');

        // Growth data for last 30 days
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Size::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.pages.sizes.analytics', compact(
            'topViewsSizes',
            'topRevenueSizes',
            'topProductSizes',
            'totalSizes',
            'activeSizes',
            'inactiveSizes',
            'totalViews',
            'totalProducts',
            'totalRevenue',
            'totalOrders',
            'growthData',
            'growthLabels'
        ));
    }

    /**
     * Show form for creating new size.
     */
    public function create()
    {
        return view('admin.pages.sizes.create');
    }

    /**
     * Store newly created size.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'code' => 'required|string|max:50|unique:sizes,code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'sizes', 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        $data['slug'] = Str::slug($request->name);
        $size = Size::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Size created successfully.',
                'size' => $size
            ]);
        }

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    /**
     * Display size details.
     */
    public function show(Size $size)
    {
        $size->incrementViewCount();
        return view('admin.pages.sizes.show', compact('size'));
    }

    /**
     * Show form for editing size.
     */
    public function edit(Size $size)
    {
        return view('admin.pages.sizes.edit', compact('size'));
    }

    /**
     * Update size.
     */
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
            'code' => 'required|string|max:50|unique:sizes,code,' . $size->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'remove_image']);

        // Handle image
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('sizes/' . $size->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'sizes', 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('sizes/' . $size->image);
            $data['image'] = null;
        }

        $size->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Size updated successfully.'
            ]);
        }

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    /**
     * Delete size.
     */
    public function destroy(Size $size)
    {
        // Check if size has products
        if ($size->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete size because it has ' . $size->products()->count() . ' products.'
            ], 422);
        }

        // Delete image
        $this->deleteImageIfExists('sizes/' . $size->image);

        $size->delete();

        return response()->json([
            'success' => true,
            'message' => 'Size deleted successfully.'
        ]);
    }

    /**
     * Toggle size status.
     */
    public function toggleStatus(Size $size)
    {
        $size->update(['status' => !$size->status]);
        return response()->json([
            'success' => true,
            'message' => 'Size status updated.',
            'status' => $size->status
        ]);
    }

    /**
     * Bulk action on sizes.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'size_ids' => 'required|string',
        ]);

        $action = $request->action;
        $sizeIds = json_decode($request->size_ids);

        if ($action === 'delete' && !auth('admin')->user()->can('delete sizes')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        if (in_array($action, ['activate', 'deactivate']) && !auth('admin')->user()->can('edit sizes')) {
            return response()->json(['success' => false, 'message' => 'Permission denied.'], 403);
        }

        $sizes = Size::whereIn('id', $sizeIds)->get();
        $count = 0;
        $errors = [];

        foreach ($sizes as $size) {
            try {
                if ($action === 'activate') {
                    $size->update(['status' => true]);
                    $count++;
                } elseif ($action === 'deactivate') {
                    $size->update(['status' => false]);
                    $count++;
                } elseif ($action === 'delete') {
                    if ($size->products()->count() > 0) {
                        $errors[] = "Cannot delete '{$size->name}' because it has products.";
                        continue;
                    }
                    $this->deleteImageIfExists('sizes/' . $size->image);
                    $size->delete();
                    $count++;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$size->name}': " . $e->getMessage();
            }
        }

        $message = "{$count} sizes processed successfully.";
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

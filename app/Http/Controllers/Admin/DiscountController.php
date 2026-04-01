<?php
// app/Http/Controllers/Admin/DiscountController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Category;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use App\Models\Fabric;
use App\Models\Occasion;
use App\Models\Collection;
use App\Models\Brand;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DiscountController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view discounts', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create discounts', only: ['create', 'store']),
            new Middleware('permission:edit discounts', only: ['edit', 'update']),
            new Middleware('permission:delete discounts', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of discounts.
     */
    public function index(Request $request)
    {
        $query = Discount::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }

        $discounts = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => Discount::count(),
            'active' => Discount::where('status', true)->count(),
            'featured' => Discount::where('is_featured', true)->count(),
            'percentage' => Discount::where('discount_type', 'percentage')->count(),
            'fixed_amount' => Discount::where('discount_type', 'fixed_amount')->count(),
            'buy_x_get_y' => Discount::where('discount_type', 'buy_x_get_y')->count(),
            'free_shipping' => Discount::where('discount_type', 'free_shipping')->count(),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.discounts.partials.discounts-table', compact('discounts'))->render();
            $pagination = $discounts->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.discounts.index', compact('discounts', 'statistics'));
    }

    /**
     * Show form for creating new discount.
     */
    public function create()
    {
        $categories = Category::where('status', true)->get();
        $subcategories = Category::whereNotNull('parent_id')->where('status', true)->get();
        $products = Product::where('status', true)->get();
        $colors = Color::where('status', true)->get();
        $sizes = Size::where('status', true)->get();
        $fabrics = Fabric::where('status', true)->get();
        $occasions = Occasion::where('status', true)->get();
        $collections = Collection::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $seasons = Season::where('status', true)->get();

        return view('admin.pages.discounts.create', compact(
            'categories',
            'subcategories',
            'products',
            'colors',
            'sizes',
            'fabrics',
            'occasions',
            'collections',
            'brands',
            'seasons'
        ));
    }

    /**
     * Store newly created discount.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code|max:50',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping',
            'discount_value' => 'required_if:discount_type,percentage,fixed_amount|nullable|numeric|min:0',
            'buy_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'get_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'target_type' => 'required|in:all_products,categories,subcategories,products,colors,sizes,fabrics,occasions,collections,brands,seasons,user_groups,min_purchase,first_purchase,holiday_special,clearance',
            'target_ids' => 'required_if:target_type,categories,subcategories,products,colors,sizes,fabrics,occasions,collections,brands,seasons|nullable|array',
            'min_purchase_amount' => 'required_if:target_type,min_purchase|nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'stackable' => 'boolean',
            'user_groups' => 'nullable|array',
        ]);

        $data = $request->except(['target_ids']);

        if ($request->has('target_ids')) {
            $data['target_ids'] = json_encode($request->target_ids);
        }

        if ($request->discount_type === 'free_shipping') {
            $data['discount_value'] = null;
            $data['buy_quantity'] = null;
            $data['get_quantity'] = null;
        }

        $discount = Discount::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Discount created successfully.',
                'discount' => $discount
            ]);
        }

        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully.');
    }

    /**
     * Display discount details.
     */
    public function show(Discount $discount)
    {
        return view('admin.pages.discounts.show', compact('discount'));
    }

    /**
     * Show form for editing discount.
     */
    public function edit(Discount $discount)
    {
        $categories = Category::where('status', true)->get();
        $subcategories = Category::whereNotNull('parent_id')->where('status', true)->get();
        $products = Product::where('status', true)->get();
        $colors = Color::where('status', true)->get();
        $sizes = Size::where('status', true)->get();
        $fabrics = Fabric::where('status', true)->get();
        $occasions = Occasion::where('status', true)->get();
        $collections = Collection::where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $seasons = Season::where('status', true)->get();

        return view('admin.pages.discounts.edit', compact(
            'discount',
            'categories',
            'subcategories',
            'products',
            'colors',
            'sizes',
            'fabrics',
            'occasions',
            'collections',
            'brands',
            'seasons'
        ));
    }

    /**
     * Update discount.
     */
    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code,' . $discount->id . '|max:50',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping',
            'discount_value' => 'required_if:discount_type,percentage,fixed_amount|nullable|numeric|min:0',
            'buy_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'get_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'target_type' => 'required|in:all_products,categories,subcategories,products,colors,sizes,fabrics,occasions,collections,brands,seasons,user_groups,min_purchase,first_purchase,holiday_special,clearance',
            'target_ids' => 'required_if:target_type,categories,subcategories,products,colors,sizes,fabrics,occasions,collections,brands,seasons|nullable|array',
            'min_purchase_amount' => 'required_if:target_type,min_purchase|nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'stackable' => 'boolean',
            'user_groups' => 'nullable|array',
        ]);

        $data = $request->except(['target_ids']);

        if ($request->has('target_ids')) {
            $data['target_ids'] = json_encode($request->target_ids);
        }

        if ($request->discount_type === 'free_shipping') {
            $data['discount_value'] = null;
            $data['buy_quantity'] = null;
            $data['get_quantity'] = null;
        }

        $discount->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Discount updated successfully.'
            ]);
        }

        return redirect()->route('admin.discounts.index')->with('success', 'Discount updated successfully.');
    }

    /**
     * Delete discount.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response()->json([
            'success' => true,
            'message' => 'Discount deleted successfully.'
        ]);
    }

    /**
     * Toggle discount status.
     */
    public function toggleStatus(Discount $discount)
    {
        $discount->update(['status' => !$discount->status]);
        return response()->json([
            'success' => true,
            'message' => 'Discount status updated.',
            'status' => $discount->status
        ]);
    }

    /**
     * Discount Analytics.
     */
    public function analytics()
    {
        // Top performing discounts
        $topDiscounts = Discount::orderBy('used_count', 'desc')->take(10)->get();

        // Discount type distribution
        $typeDistribution = [
            'percentage' => Discount::where('discount_type', 'percentage')->count(),
            'fixed_amount' => Discount::where('discount_type', 'fixed_amount')->count(),
            'buy_x_get_y' => Discount::where('discount_type', 'buy_x_get_y')->count(),
            'free_shipping' => Discount::where('discount_type', 'free_shipping')->count(),
        ];

        // Target type distribution
        $targetDistribution = [];
        $targetTypes = [
            'all_products',
            'categories',
            'products',
            'colors',
            'sizes',
            'fabrics',
            'occasions',
            'collections',
            'brands',
            'seasons'
        ];

        foreach ($targetTypes as $type) {
            $targetDistribution[$type] = Discount::where('target_type', $type)->count();
        }

        // Active vs Inactive
        $activeCount = Discount::where('status', true)->count();
        $inactiveCount = Discount::where('status', false)->count();

        // Total usage
        $totalUsage = Discount::sum('used_count');

        // Growth data
        $growthData = [];
        $growthLabels = [];

        for ($i = 29; $i >= 0; $i--) {
            $growthLabels[] = now()->subDays($i)->format('M d');
            $count = Discount::whereDate('created_at', now()->subDays($i))->count();
            $growthData[] = $count;
        }

        return view('admin.discounts.analytics', compact(
            'topDiscounts',
            'typeDistribution',
            'targetDistribution',
            'activeCount',
            'inactiveCount',
            'totalUsage',
            'growthData',
            'growthLabels'
        ));
    }

    /**
     * Bulk action on discounts.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'discount_ids' => 'required|string',
        ]);

        $action = $request->action;
        $discountIds = json_decode($request->discount_ids);

        $discounts = Discount::whereIn('id', $discountIds)->get();
        $count = 0;

        foreach ($discounts as $discount) {
            if ($action === 'activate') {
                $discount->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $discount->update(['status' => false]);
                $count++;
            } elseif ($action === 'delete') {
                $discount->delete();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} discounts {$action}d successfully."
        ]);
    }
}

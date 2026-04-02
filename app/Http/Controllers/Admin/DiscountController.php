<?php
// app/Http/Controllers/Admin/DiscountController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.pages.discounts.index', compact('discounts'));
    }

    public function create()
    {
        $products = Product::where('status', true)->orderBy('name')->get();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->where('status', true)->orderBy('name')->get();
        $colors = Color::where('status', true)->orderBy('name')->get();
        $sizes = Size::where('status', true)->orderBy('name')->get();

        // Get all dynamic custom attributes
        $customAttributes = Attribute::with('values')
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view('admin.pages.discounts.create', compact('products', 'categories', 'subcategories', 'colors', 'sizes', 'customAttributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code|max:50',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping',
            'discount_value' => 'required_if:discount_type,percentage,fixed_amount|nullable|numeric|min:0',
            'buy_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'get_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'free_shipping_only' => 'boolean',
            'target_type' => 'required|in:all_products,products,categories,subcategories,colors,sizes,custom_attributes',
            'target_ids' => 'nullable|array',
            'target_ids.*' => 'string|distinct',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'stackable' => 'boolean',
            'user_groups' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $discount = Discount::create([
                'name' => $validated['name'],
                'code' => Str::upper($validated['code']),
                'description' => $validated['description'] ?? null,
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'] ?? null,
                'buy_quantity' => $validated['buy_quantity'] ?? null,
                'get_quantity' => $validated['get_quantity'] ?? null,
                'free_shipping_only' => $request->has('free_shipping_only'),
                'target_type' => $validated['target_type'],
                'target_ids' => $validated['target_ids'] ?? null,
                'min_purchase_amount' => $validated['min_purchase_amount'] ?? null,
                'max_usage_per_user' => $validated['max_usage_per_user'] ?? null,
                'total_usage_limit' => $validated['total_usage_limit'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $request->has('status'),
                'is_featured' => $request->has('is_featured'),
                'stackable' => $request->has('stackable'),
                'user_groups' => $validated['user_groups'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Discount created successfully.',
                'discount' => $discount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create discount: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Discount $discount)
    {
        $products = Product::where('status', true)->orderBy('name')->get();
        $categories = Category::where('status', true)->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->where('status', true)->orderBy('name')->get();
        $colors = Color::where('status', true)->orderBy('name')->get();
        $sizes = Size::where('status', true)->orderBy('name')->get();
        $customAttributes = Attribute::with('values')->where('status', true)->orderBy('name')->get();

        return view('admin.pages.discounts.edit', compact('discount', 'products', 'categories', 'subcategories', 'colors', 'sizes', 'customAttributes'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code,' . $discount->id . '|max:50',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping',
            'discount_value' => 'required_if:discount_type,percentage,fixed_amount|nullable|numeric|min:0',
            'buy_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'get_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'free_shipping_only' => 'boolean',
            'target_type' => 'required|in:all_products,products,categories,subcategories,colors,sizes,custom_attributes',
            'target_ids' => 'nullable|array',
            'target_ids.*' => 'string|distinct',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'stackable' => 'boolean',
            'user_groups' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $discount->update([
                'name' => $validated['name'],
                'code' => Str::upper($validated['code']),
                'description' => $validated['description'] ?? null,
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'] ?? null,
                'buy_quantity' => $validated['buy_quantity'] ?? null,
                'get_quantity' => $validated['get_quantity'] ?? null,
                'free_shipping_only' => $request->has('free_shipping_only'),
                'target_type' => $validated['target_type'],
                'target_ids' => $validated['target_ids'] ?? null,
                'min_purchase_amount' => $validated['min_purchase_amount'] ?? null,
                'max_usage_per_user' => $validated['max_usage_per_user'] ?? null,
                'total_usage_limit' => $validated['total_usage_limit'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $request->has('status'),
                'is_featured' => $request->has('is_featured'),
                'stackable' => $request->has('stackable'),
                'user_groups' => $validated['user_groups'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Discount updated successfully.',
                'discount' => $discount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update discount: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();

            return response()->json([
                'success' => true,
                'message' => 'Discount deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete discount: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get active discounts for a product
    public function getProductDiscounts(Product $product)
    {
        $discounts = Discount::where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->get();

        $applicableDiscounts = [];
        foreach ($discounts as $discount) {
            if ($discount->isProductEligible($product)) {
                $applicableDiscounts[] = $discount->getDiscountInfo($product);
            }
        }

        return response()->json($applicableDiscounts);
    }
}

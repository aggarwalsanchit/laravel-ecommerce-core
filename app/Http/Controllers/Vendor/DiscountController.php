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
        $customAttributes = Attribute::with('values')->where('status', true)->orderBy('name')->get();

        return view('admin.pages.discounts.create', compact('products', 'categories', 'subcategories', 'colors', 'sizes', 'customAttributes'));
    }

    public function store(Request $request)
    {
        // Clean duplicate values before validation
        $cleanedData = $this->cleanDuplicateValues($request);

        $validated = validator($cleanedData, [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code|max:50',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping',
            'discount_value' => 'required_if:discount_type,percentage,fixed_amount|nullable|numeric|min:0',
            'buy_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'get_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'free_shipping_only' => 'boolean',
            'target_type' => 'required|in:all_products,products,categories,subcategories,colors,sizes,custom_attributes,user_groups,min_purchase,first_purchase,holiday_special,clearance',
            'target_ids' => 'nullable|array',
            'target_ids.*' => 'nullable|string',
            'attribute_id' => 'required_if:target_type,custom_attributes|nullable|exists:attributes,id',
            'attribute_value_ids' => 'required_if:target_type,custom_attributes|nullable|array',
            'attribute_value_ids.*' => 'exists:attribute_values,id',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'stackable' => 'boolean',
            'user_groups' => 'nullable|array',
            'user_groups.*' => 'string',
        ])->validate();

        DB::beginTransaction();

        try {
            // Prepare target_ids based on target_type
            $targetIds = null;

            if ($request->target_type === 'custom_attributes') {
                $targetIds = [
                    'attribute_id' => $request->attribute_id,
                    'attribute_value_ids' => array_values(array_unique(array_filter($request->attribute_value_ids ?? [])))
                ];
            } else {
                $targetIds = !empty($request->target_ids) ? array_values(array_unique(array_filter($request->target_ids))) : null;
            }

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
                'target_ids' => $targetIds,
                'min_purchase_amount' => $validated['min_purchase_amount'] ?? null,
                'max_usage_per_user' => $validated['max_usage_per_user'] ?? null,
                'total_usage_limit' => $validated['total_usage_limit'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $request->has('status'),
                'is_featured' => $request->has('is_featured'),
                'stackable' => $request->has('stackable'),
                'user_groups' => !empty($request->user_groups) ? array_values(array_unique(array_filter($request->user_groups))) : null,
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

        // Get selected attribute value IDs for custom attributes
        $selectedAttributeValueIds = [];
        $selectedAttributeId = null;
        if ($discount->target_type === 'custom_attributes' && is_array($discount->target_ids)) {
            $selectedAttributeId = $discount->target_ids['attribute_id'] ?? null;
            $selectedAttributeValueIds = $discount->target_ids['attribute_value_ids'] ?? [];
        }

        return view('admin.pages.discounts.edit', compact('discount', 'products', 'categories', 'subcategories', 'colors', 'sizes', 'customAttributes', 'selectedAttributeValueIds', 'selectedAttributeId'));
    }

    public function update(Request $request, Discount $discount)
    {
        // Clean duplicate values before validation
        $cleanedData = $this->cleanDuplicateValues($request);

        $validated = validator($cleanedData, [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discounts,code,' . $discount->id . '|max:50',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed_amount,buy_x_get_y,free_shipping',
            'discount_value' => 'required_if:discount_type,percentage,fixed_amount|nullable|numeric|min:0',
            'buy_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'get_quantity' => 'required_if:discount_type,buy_x_get_y|nullable|integer|min:1',
            'free_shipping_only' => 'boolean',
            'target_type' => 'required|in:all_products,products,categories,subcategories,colors,sizes,custom_attributes,user_groups,min_purchase,first_purchase,holiday_special,clearance',
            'target_ids' => 'nullable|array',
            'target_ids.*' => 'nullable|string',
            'attribute_id' => 'required_if:target_type,custom_attributes|nullable|exists:attributes,id',
            'attribute_value_ids' => 'required_if:target_type,custom_attributes|nullable|array',
            'attribute_value_ids.*' => 'exists:attribute_values,id',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_usage_per_user' => 'nullable|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'stackable' => 'boolean',
            'user_groups' => 'nullable|array',
            'user_groups.*' => 'string',
        ])->validate();

        DB::beginTransaction();

        try {
            // Prepare target_ids based on target_type
            $targetIds = null;

            if ($request->target_type === 'custom_attributes') {
                $targetIds = [
                    'attribute_id' => $request->attribute_id,
                    'attribute_value_ids' => array_values(array_unique(array_filter($request->attribute_value_ids ?? [])))
                ];
            } else {
                $targetIds = !empty($request->target_ids) ? array_values(array_unique(array_filter($request->target_ids))) : null;
            }

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
                'target_ids' => $targetIds,
                'min_purchase_amount' => $validated['min_purchase_amount'] ?? null,
                'max_usage_per_user' => $validated['max_usage_per_user'] ?? null,
                'total_usage_limit' => $validated['total_usage_limit'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $request->has('status'),
                'is_featured' => $request->has('is_featured'),
                'stackable' => $request->has('stackable'),
                'user_groups' => !empty($request->user_groups) ? array_values(array_unique(array_filter($request->user_groups))) : null,
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

    public function getAttributeValues($attributeId)
    {
        $attribute = Attribute::with('values')->findOrFail($attributeId);
        return response()->json($attribute->values);
    }

    private function cleanDuplicateValues(Request $request)
    {
        $data = $request->all();

        if (isset($data['target_ids']) && is_array($data['target_ids'])) {
            $data['target_ids'] = array_values(array_unique(array_filter($data['target_ids'])));
        }

        if (isset($data['attribute_value_ids']) && is_array($data['attribute_value_ids'])) {
            $data['attribute_value_ids'] = array_values(array_unique(array_filter($data['attribute_value_ids'])));
        }

        if (isset($data['user_groups']) && is_array($data['user_groups'])) {
            $data['user_groups'] = array_values(array_unique(array_filter($data['user_groups'])));
        }

        $request->replace($data);
        return $data;
    }
}

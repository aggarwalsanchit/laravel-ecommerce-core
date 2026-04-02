<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller implements HasMiddleware
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
            new Middleware('permission:view products', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create products', only: ['create', 'store']),
            new Middleware('permission:edit products', only: ['edit', 'update']),
            new Middleware('permission:delete products', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['mainCategory', 'images', 'variants']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        // Filter by featured
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'stock':
                $query->orderBy('stock', $sortOrder);
                break;
            case 'view_count':
                $query->orderBy('view_count', 'desc');
                break;
            case 'order_count':
                $query->orderBy('order_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $products = $query->paginate(15);

        $statistics = [
            'total' => Product::count(),
            'active' => Product::where('status', true)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'on_sale' => Product::where('is_on_sale', true)->count(),
            'out_of_stock' => Product::where('stock_status', 'out_of_stock')->count(),
            'total_views' => Product::sum('view_count'),
            'total_revenue' => Product::sum('total_sold'),
        ];

        $categories = Category::where('status', true)->get();

        if ($request->ajax()) {
            $table = view('admin.pages.products.partials.products-table', compact('products'))->render();
            $pagination = $products->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.products.index', compact('products', 'statistics', 'categories'));
    }

    /**
     * Show form for creating new product.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->where('status', true)->orderBy('name')->get();
        $subcategories = Category::whereNotNull('parent_id')->where('status', true)->orderBy('name')->get();
        $colors = Color::where('status', true)->orderBy('name')->get();
        $sizes = Size::where('status', true)->orderBy('name')->get();

        // Get custom attributes (universal attributes)
        $customAttributes = Attribute::with('values')
            ->where('status', true)
            ->orderBy('display_order')
            ->get();

        return view('admin.pages.products.create', compact('categories', 'subcategories', 'colors', 'sizes', 'customAttributes'));
    }

    /**
     * Store newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'variants' => 'nullable|array',
            'tier_prices' => 'nullable|array',
            'custom_attributes' => 'nullable|array',
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            $data = $request->except(['featured_image', 'gallery_images', 'subcategories', 'colors', 'sizes', 'variants', 'tier_prices', 'custom_attributes']);

            // Generate slug
            $data['slug'] = Str::slug($request->name);

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                $compressed = $this->imageCompressor->compress($request->file('featured_image'), 'products', 800, 85);
                if ($compressed['success']) {
                    $data['featured_image'] = $compressed['filename'];
                }
            }

            // Create product
            $product = Product::create($data);

            // Handle subcategories
            if ($request->has('subcategories') && !empty($request->subcategories)) {
                $product->subcategories()->sync($request->subcategories);
            }

            // Handle colors
            if ($request->has('colors') && !empty($request->colors)) {
                $colorData = [];
                foreach ($request->colors as $colorId) {
                    $colorData[$colorId] = ['color_image' => null];
                }
                $product->colors()->sync($colorData);
            }

            // Handle sizes
            if ($request->has('sizes') && !empty($request->sizes)) {
                $sizeData = [];
                foreach ($request->sizes as $sizeId) {
                    $sizeData[$sizeId] = ['stock' => 0, 'price_adjustment' => 0];
                }
                $product->sizes()->sync($sizeData);
            }

            // Handle gallery images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $compressed = $this->imageCompressor->compress($image, 'products/gallery', 800, 85);
                    if ($compressed['success']) {
                        $product->images()->create([
                            'image_path' => 'products/gallery/' . $compressed['filename'],
                            'is_featured' => $index === 0,
                            'order' => $index,
                        ]);
                    }
                }
            }

            // Handle variants (color + size combinations)
            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $variant) {
                    if (!empty($variant['sku']) && !empty($variant['price'])) {
                        $product->variants()->create([
                            'color_id' => $variant['color_id'] ?? null,
                            'size_id' => $variant['size_id'] ?? null,
                            'sku' => $variant['sku'],
                            'price' => $variant['price'],
                            'sale_price' => $variant['sale_price'] ?? null,
                            'stock' => $variant['stock'] ?? 0,
                            'status' => true,
                        ]);
                    }
                }
            }

            // Handle tiered pricing - ONLY if pricing_type is 'tiered'
            if ($request->pricing_type === 'tiered' && $request->has('tier_prices') && is_array($request->tier_prices)) {
                foreach ($request->tier_prices as $tier) {
                    if (!empty($tier['min_quantity']) && !empty($tier['price'])) {
                        $product->tierPrices()->create([
                            'min_quantity' => $tier['min_quantity'],
                            'max_quantity' => $tier['max_quantity'] ?? null,
                            'price' => $tier['price'],
                        ]);
                    }
                }
            }

            // Handle custom attributes (universal attributes) - FIXED
            if ($request->has('custom_attributes') && is_array($request->custom_attributes)) {
                $insertData = [];
                foreach ($request->custom_attributes as $attributeId => $value) {
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            if (!empty($val) && is_numeric($val)) {
                                $insertData[] = [
                                    'product_id' => $product->id,
                                    'attribute_value_id' => (int)$val,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    } else {
                        if (!empty($value) && is_numeric($value)) {
                            $insertData[] = [
                                'product_id' => $product->id,
                                'attribute_value_id' => (int)$value,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }

                if (!empty($insertData)) {
                    DB::table('product_custom_attributes')->insert($insertData);
                }
            }

            // Commit transaction
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully.',
                    'product' => $product
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            \Log::error('Product creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to create product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display product details.
     */
    public function show(Product $product)
    {
        $product->load(['mainCategory', 'subcategories', 'colors', 'sizes', 'variants', 'images', 'tierPrices', 'customAttributes']);
        $product->incrementViewCount();

        return view('admin.pages.products.show', compact('product'));
    }

    /**
     * Show form for editing product.
     */
    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->where('status', true)->orderBy('name')->get();
        $colors = Color::where('status', true)->orderBy('name')->get();
        $sizes = Size::where('status', true)->orderBy('name')->get();
        $customAttributes = Attribute::with('values')->where('status', true)->orderBy('display_order')->get();

        // Load the product with its relationships
        $product->load(['subcategories', 'colors', 'sizes', 'variants', 'tierPrices', 'customAttributes']);

        // Get selected color and size IDs for the product
        $productColors = $product->colors->pluck('id')->toArray();
        $productSizes = $product->sizes->pluck('id')->toArray();

        // Get selected subcategory IDs
        $productSubcategories = $product->subcategories->pluck('id')->toArray();

        // Create a map of existing custom attribute VALUE IDs
        $existingCustomAttributes = [];
        foreach ($product->customAttributes as $attrValue) {
            $attributeId = $attrValue->attribute_id;
            if (!isset($existingCustomAttributes[$attributeId])) {
                $existingCustomAttributes[$attributeId] = [];
            }
            // Use attribute_value_id if available, otherwise use value
            $valueToStore = $attrValue->attribute_value_id ?? $attrValue->value;
            $existingCustomAttributes[$attributeId][] = $valueToStore;
        }

        // Debug: Log to see what we have
        \Log::info('=== EDIT PRODUCT DEBUG ===');
        \Log::info('Product ID: ' . $product->id);
        \Log::info('Custom Attributes Count: ' . $product->customAttributes->count());
        \Log::info('Existing Custom Attributes: ', $existingCustomAttributes);
        \Log::info('Product Custom Attributes Raw: ', $product->customAttributes->toArray());

        // Also log attribute values for comparison
        foreach ($customAttributes as $attribute) {
            \Log::info('Attribute: ' . $attribute->name . ' (ID: ' . $attribute->id . ')');
            \Log::info('  Values: ', $attribute->values->pluck('id', 'value')->toArray());
            \Log::info('  Saved IDs: ', [$existingCustomAttributes[$attribute->id] ?? []]);
        }

        return view('admin.pages.products.edit', compact(
            'product',
            'categories',
            'colors',
            'sizes',
            'customAttributes',
            'productColors',
            'productSizes',
            'productSubcategories',
            'existingCustomAttributes'
        ));
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'track_stock' => 'nullable|boolean',
            'stock' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:70',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'pricing_type' => 'required|in:single,tiered',
            'featured_image_id' => 'nullable|exists:product_images,id',
            'featured_image_index' => 'nullable|integer',
            'deleted_images' => 'nullable|string',
            'product_images.*' => 'nullable|image|max:2048',
            'subcategories' => 'nullable|array',
            'colors' => 'nullable|array',
            'sizes' => 'nullable|array',
            'variants' => 'nullable|array',
            'tier_prices' => 'nullable|array',
            'custom_attributes' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Update basic product information
            $product->update([
                'name' => $request->name,
                'sku' => $request->sku,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sale_start_date' => $request->sale_start_date ? date('Y-m-d', strtotime($request->sale_start_date)) : null,
                'sale_end_date' => $request->sale_end_date ? date('Y-m-d', strtotime($request->sale_end_date)) : null,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'track_stock' => $request->has('track_stock'),
                'stock' => $request->has('track_stock') ? ($request->stock ?? 0) : 0,
                'low_stock_threshold' => $request->low_stock_threshold ?? 5,
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
                'status' => $request->has('status'),
                'is_featured' => $request->has('is_featured'),
                'is_new' => $request->has('is_new'),
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
                'pricing_type' => $request->pricing_type,
            ]);

            // Handle subcategories
            if ($request->has('subcategories')) {
                $product->subcategories()->sync($request->subcategories);
            } else {
                $product->subcategories()->sync([]);
            }

            // Handle colors
            if ($request->has('colors')) {
                $product->colors()->sync($request->colors);
            } else {
                $product->colors()->sync([]);
            }

            // Handle sizes
            if ($request->has('sizes')) {
                $product->sizes()->sync($request->sizes);
            } else {
                $product->sizes()->sync([]);
            }

            // Handle variants
            $product->variants()->delete();
            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $variant) {
                    if (!empty($variant['color_id']) && !empty($variant['size_id'])) {
                        $product->variants()->create([
                            'color_id' => $variant['color_id'],
                            'size_id' => $variant['size_id'],
                            'sku' => $variant['sku'] ?? null,
                            'price' => $variant['price'] ?? $request->price,
                            'sale_price' => $variant['sale_price'] ?? $request->sale_price,
                            'stock' => $variant['stock'] ?? 0,
                        ]);
                    }
                }
            }

            // Handle tiered pricing
            $product->tierPrices()->delete();
            if ($request->pricing_type === 'tiered' && $request->has('tier_prices')) {
                foreach ($request->tier_prices as $tier) {
                    if (!empty($tier['min_quantity']) && !empty($tier['price'])) {
                        $product->tierPrices()->create([
                            'min_quantity' => $tier['min_quantity'],
                            'max_quantity' => $tier['max_quantity'] ?? null,
                            'price' => $tier['price'],
                        ]);
                    }
                }
            }

            // Handle images
            if ($request->has('deleted_images') && $request->deleted_images) {
                $deletedImageIds = json_decode($request->deleted_images, true);
                if (!empty($deletedImageIds) && is_array($deletedImageIds)) {
                    $imagesToDelete = $product->images()->whereIn('id', $deletedImageIds)->get();
                    foreach ($imagesToDelete as $image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'original_name' => $image->getClientOriginalName(),
                        'file_size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                        'is_featured' => false,
                    ]);
                }
            }

            // Handle featured image
            $product->images()->update(['is_featured' => false]);

            if ($request->has('featured_image_id') && $request->featured_image_id) {
                $product->images()->where('id', $request->featured_image_id)->update(['is_featured' => true]);
            } elseif ($request->has('featured_image_index') && $request->featured_image_index >= 0) {
                $newImages = $product->images()->latest()->take($request->hasFile('product_images') ? count($request->file('product_images')) : 0)->get();
                if ($newImages->count() > $request->featured_image_index) {
                    $newImages[$request->featured_image_index]->update(['is_featured' => true]);
                }
            } elseif ($product->images()->count() > 0) {
                $product->images()->first()->update(['is_featured' => true]);
            }

            // ========== HANDLE CUSTOM ATTRIBUTES - FIXED ==========
            // Delete all existing custom attributes for this product
            \DB::table('product_custom_attributes')->where('product_id', $product->id)->delete();

            // Save new custom attributes
            if ($request->has('custom_attributes') && is_array($request->custom_attributes)) {
                foreach ($request->custom_attributes as $attributeId => $value) {
                    // Skip empty values
                    if ($value === null || $value === '' || $value === []) {
                        continue;
                    }

                    // Handle array values (multiselect)
                    if (is_array($value)) {
                        foreach ($value as $singleValueId) {
                            if ($singleValueId !== null && $singleValueId !== '') {
                                \DB::table('product_custom_attributes')->insert([
                                    'product_id' => $product->id,
                                    // 'attribute_id' => $attributeId,
                                    'attribute_value_id' => $singleValueId,  // Save the VALUE ID
                                    // 'value' => null,  // Or you can store the text value here too
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    } else {
                        // Handle single values
                        \DB::table('product_custom_attributes')->insert([
                            'product_id' => $product->id,
                            // 'attribute_id' => $attributeId,
                            'attribute_value_id' => $value,  // Save the VALUE ID
                            // 'value' => null,  // Or you can store the text value here too
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            \Log::info('Product updated successfully', [
                'product_id' => $product->id,
                'sale_start_date' => $product->sale_start_date,
                'sale_end_date' => $product->sale_end_date,
                'custom_attributes_count' => \DB::table('product_custom_attributes')->where('product_id', $product->id)->count()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'product' => $product->fresh(['images', 'variants', 'tierPrices'])
                ]);
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete product.
     */
    public function destroy(Product $product)
    {
        // Delete images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            if ($image->thumbnail_path) {
                Storage::disk('public')->delete($image->thumbnail_path);
            }
        }

        // Delete featured image
        if ($product->featured_image) {
            Storage::disk('public')->delete('products/' . $product->featured_image);
        }

        // Delete variant images
        foreach ($product->variants as $variant) {
            if ($variant->image) {
                Storage::disk('public')->delete('products/variants/' . $variant->image);
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);
        return response()->json([
            'success' => true,
            'message' => 'Product status updated.',
            'status' => $product->status
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $product->is_featured
        ]);
    }

    /**
     * Product analytics.
     */


    /**
     * Bulk action on products.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'product_ids' => 'required|string',
        ]);

        $action = $request->action;
        $productIds = json_decode($request->product_ids);

        $products = Product::whereIn('id', $productIds)->get();
        $count = 0;

        foreach ($products as $product) {
            if ($action === 'activate') {
                $product->update(['status' => true]);
                $count++;
            } elseif ($action === 'deactivate') {
                $product->update(['status' => false]);
                $count++;
            } elseif ($action === 'feature') {
                $product->update(['is_featured' => true]);
                $count++;
            } elseif ($action === 'unfeature') {
                $product->update(['is_featured' => false]);
                $count++;
            } elseif ($action === 'delete') {
                // Delete images
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                if ($product->featured_image) {
                    Storage::disk('public')->delete('products/' . $product->featured_image);
                }
                $product->delete();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} products {$action}d successfully."
        ]);
    }

    public function analytics(Product $product, Request $request)
    {
        $dateRange = $request->get('date_range', '30days');
        $startDate = now()->subDays($this->getDaysFromRange($dateRange));

        $dailyStats = \DB::table('product_analytics_logs')
            ->where('product_id', $product->id)
            ->where('created_at', '>=', $startDate)
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('COUNT(CASE WHEN event_type = "view" THEN 1 END) as views'),
                \DB::raw('COUNT(CASE WHEN event_type = "add_to_cart" THEN 1 END) as adds_to_cart'),
                \DB::raw('COUNT(CASE WHEN event_type = "order" THEN 1 END) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $dailyStats->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();

        // Return view instead of JSON
        return view('admin.pages.products.analytics', compact(
            'product',
            'dailyStats',
            'chartLabels',
            'dateRange'
        ));
    }

    private function getDaysFromRange($range)
    {
        switch ($range) {
            case '7days':
                return 7;
            case '30days':
                return 30;
            case '90days':
                return 90;
            case 'year':
                return 365;
            default:
                return 30;
        }
    }

    public function getFreshData()
    {
        try {
            // Get all categories with proper hierarchy
            $allCategories = Category::where('status', true)
                ->orderBy('name')
                ->get();

            // Build hierarchical categories for dropdown
            $categoriesForDropdown = $this->buildCategoryTree($allCategories);

            $colors = Color::where('status', true)
                ->orderBy('name')
                ->get(['id', 'name']);

            $sizes = Size::where('status', true)
                ->orderBy('name')
                ->get(['id', 'name']);

            // Get main categories (parent_id = null)
            $mainCategories = Category::whereNull('parent_id')
                ->where('status', true)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json([
                'success' => true,
                'main_categories' => $mainCategories,
                'all_categories' => $categoriesForDropdown,
                'colors' => $colors,
                'sizes' => $sizes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function buildCategoryTree($categories, $parentId = null, $depth = 0)
    {
        $result = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->depth = $depth;
                $result[] = $category;
                $children = $this->buildCategoryTree($categories, $category->id, $depth + 1);
                $result = array_merge($result, $children);
            }
        }
        return $result;
    }
}

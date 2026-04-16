<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductAttributeValue;
use App\Models\ProductTierPrice;
use App\Models\ProductDailyAnalytic;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Size;
use App\Models\Attribute;
use App\Models\Tag;
use App\Models\Discount;
use App\Models\Vendor;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin',
            // new Middleware('permission:view_all_products', only: ['index', 'show', 'analytics']),
            // new Middleware('permission:create_all_products', only: ['create', 'store']),
            // new Middleware('permission: edit_all_products', only: ['edit', 'update', 'toggleStatus', 'toggleFeatured', 'bulkAction', 'approveProduct', 'rejectProduct']),
            // new Middleware('permission:delete_all_products', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'vendor', 'mainImage']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        // Filter by approval status (for vendor products)
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by vendor
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'true');
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(20);

        // Get aggregated analytics (total views, orders, revenue) from daily analytics table
        foreach ($products as $product) {
            $product->total_views = $product->dailyAnalytics()->sum('views');
            $product->total_orders = $product->dailyAnalytics()->sum('orders');
            $product->total_revenue = $product->dailyAnalytics()->sum('revenue');
        }

        $statistics = [
            'total' => Product::count(),
            'active' => Product::where('status', true)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'pending_approval' => Product::where('approval_status', 'pending')->count(),
            'out_of_stock' => Product::where('stock_status', 'outofstock')->count(),
        ];

        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::active()->orderBy('name')->get();

        return view('admin.pages.products.index', compact('products', 'statistics', 'categories', 'brands'));
    }

    /**
     * Show form for creating a new product.
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('order')
            ->get();
        $brands = Brand::active()->orderBy('name')->get();
        $colors = Color::active()->orderBy('name')->get();
        $sizes = Size::active()->orderBy('name')->get();
        $attributes = Attribute::active()->with('values')->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        $vendors = Vendor::where('is_owner', 1)->get(); // if you have vendors

        return view('admin.pages.products.create', compact(
            'categories',
            'parentCategories',
            'brands',
            'colors',
            'sizes',
            'attributes',
            'tags',
            'vendors'
        ));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'barcode' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'brand_id' => 'nullable|exists:brands,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'is_wholesale' => 'boolean',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'is_range' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_stock' => 'boolean',
            'allow_backorder' => 'boolean',
            'stock_status' => 'required|in:instock,outofstock,backorder',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'free_shipping' => 'boolean',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_new' => 'boolean',
            'sort_order' => 'nullable|integer',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'focus_keyword' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'primary_category_id' => 'nullable|exists:categories,id',
            'additional_categories' => 'nullable',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|array',
            'new_tags.*' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'images_alt' => 'nullable|array',
            'images_alt.*' => 'nullable|string',
            'images_is_main' => 'nullable|array',
            'videos' => 'nullable|array',
            'videos.*.url' => 'nullable|url',
            'videos.*.title' => 'nullable|string',
            'variants' => 'nullable|array',
            'variants.*.color_id' => 'nullable|exists:colors,id',
            'variants.*.size_id' => 'nullable|exists:sizes,id',
            'variants.*.sku' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric',
            'variants.*.compare_price' => 'nullable|numeric',
            'variants.*.wholesale_price' => 'nullable|numeric',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
            'variants.*.image_alt' => 'nullable|string',
            'attributes' => 'nullable|array',
            'tier_prices' => 'nullable|array',
            'discounts' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // Prepare product data
            $data = $request->except([
                'images',
                'videos',
                'variants',
                'attributes',
                'tier_prices',
                'discounts',
                'categories',
                'tags',
                'new_tags',
                'og_image',
                'additional_categories',
                'primary_category_id',
                'images_alt',
                'images_is_main',
                'variants'
            ]);

            $data['slug'] = $request->slug ?: Str::slug($request->name);
            $data['highlights'] = $request->highlights ? json_encode($request->highlights) : null;

            // Set boolean flags
            $data['is_wholesale'] = $request->has('is_wholesale');
            $data['is_range'] = $request->has('is_range');
            $data['track_stock'] = $request->has('track_stock');
            $data['allow_backorder'] = $request->has('allow_backorder');
            $data['free_shipping'] = $request->has('free_shipping');
            $data['status'] = $request->has('status');
            $data['is_featured'] = $request->has('is_featured');
            $data['is_bestseller'] = $request->has('is_bestseller');
            $data['is_new'] = $request->has('is_new');

            $product = Product::create($data);

            // Handle Primary Category
            if ($request->has('primary_category_id') && $request->primary_category_id) {
                $product->primary_category_id = $request->primary_category_id;
                $product->save();
            }

            // Handle Additional Categories (from JSON string)
            if ($request->has('additional_categories')) {
                $additionalCategories = $request->additional_categories;

                // If it's a JSON string, decode it
                if (is_string($additionalCategories)) {
                    $additionalCategories = json_decode($additionalCategories, true);
                }

                // If it's already an array, use it directly
                if (is_array($additionalCategories) && !empty($additionalCategories)) {
                    // Filter out the primary category if it's in additional categories
                    $primaryId = $request->primary_category_id;
                    if ($primaryId) {
                        $additionalCategories = array_filter($additionalCategories, function ($catId) use ($primaryId) {
                            return $catId != $primaryId;
                        });
                    }

                    if (!empty($additionalCategories)) {
                        $product->categories()->attach($additionalCategories);
                    }
                }
            }

            // Handle Tags - Combine selected tags and new tags
            $allTagIds = [];

            // Get existing tag IDs from select2
            if ($request->has('tags') && is_array($request->tags)) {
                $allTagIds = array_merge($allTagIds, $request->tags);
            }

            // Handle new tags from comma input
            if ($request->has('new_tags') && is_array($request->new_tags)) {
                foreach ($request->new_tags as $newTagName) {
                    if (!empty($newTagName)) {
                        $existingTag = Tag::where('name', $newTagName)->first();
                        if ($existingTag) {
                            $allTagIds[] = $existingTag->id;
                        } else {
                            $newTag = Tag::create([
                                'name' => $newTagName,
                                'slug' => Str::slug($newTagName),
                            ]);
                            $allTagIds[] = $newTag->id;
                        }
                    }
                }
            }

            if (!empty($allTagIds)) {
                $product->tags()->sync($allTagIds);
            }

            // Handle OG image
            if ($request->hasFile('og_image')) {
                $compressed = $this->imageCompressor->compress($request->file('og_image'), 'products/og', 1200, 85);
                if ($compressed['success']) {
                    $product->update(['og_image' => $compressed['filename']]);
                }
            }

            // Handle product images
            if ($request->hasFile('images')) {
                $imageFiles = array_values($request->file('images'));
                $altTexts = $request->images_alt ?? [];
                $isMains = $request->images_is_main ?? [];

                foreach ($imageFiles as $index => $imageFile) {
                    $compressed = $this->imageCompressor->compress($imageFile, 'products', 800, 85);
                    if ($compressed['success']) {

                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $compressed['filename'],
                            'alt_text' => $altTexts[$index] ?? null,
                            'sort_order' => $index,
                            'is_main' => isset($isMains[$index]) && $isMains[$index] == '1',
                        ]);
                    }
                }
            }

            // Handle videos
            if ($request->has('videos') && is_array($request->videos)) {
                foreach ($request->videos as $video) {
                    if (!empty($video['url'])) {
                        $product->videos()->create($video);
                    }
                }
            }

            // Handle variants (colors, sizes)
            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $variantData) {
                    // Handle variant image if present
                    if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $compressed = $this->imageCompressor->compress($variantData['image'], 'variants', 300, 85);
                        if ($compressed['success']) {
                            $variantData['image'] = $compressed['filename'];
                        }
                    }
                    $variantData['product_id'] = $product->id;
                    ProductVariant::create($variantData);
                }
            }

            // Handle custom attribute values - FIXED
            if ($request->has('attributes')) {
                $attributes = $request->input('attributes');

                if (is_array($attributes) && !empty($attributes)) {
                    foreach ($attributes as $attrId => $value) {
                        // Skip empty values
                        if ($value === null || $value === '') {
                            continue;
                        }

                        $attributeValueId = null;
                        $savedValue = $value;

                        // Check if this attribute has predefined values in attribute_values table
                        $attribute = Attribute::find($attrId);
                        if ($attribute && $attribute->values()->exists()) {
                            // Find the attribute_value_id that matches the selected value
                            $attributeValue = $attribute->values()
                                ->where('value', $value)
                                ->orWhere('label', $value)
                                ->first();

                            if ($attributeValue) {
                                $attributeValueId = $attributeValue->id;
                                $savedValue = $attributeValue->value;
                            }
                        }

                        // Handle array values (for multiselect)
                        if (is_array($value)) {
                            // For multiselect, we need to save multiple rows or JSON
                            // Option 1: Save as JSON
                            $savedValue = json_encode($value);
                        }

                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attrId,
                            'attribute_value_id' => $attributeValueId,
                            'value' => $savedValue,
                        ]);
                    }
                }
            }

            // Handle tier pricing
            if ($request->has('tier_prices') && is_array($request->tier_prices)) {
                foreach ($request->tier_prices as $tier) {
                    if (!empty($tier['min_quantity']) && !empty($tier['price'])) {
                        $tier['product_id'] = $product->id;
                        ProductTierPrice::create($tier);
                    }
                }
            }

            DB::commit();

            $this->logActivity(
                'create',
                'product',
                'admin',
                $product->id,
                $product->name,
                null,
                $product->toArray(),
                "Created new product: {$product->name}"
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product created successfully.',
                    'product' => $product
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create product: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to create product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display product details.
     */
    public function show(Product $product)
    {
        // Load all necessary relationships
        $product->load([
            'brand',
            'vendor',
            'categories',
            'tags',
            'images',
            'videos',
            'variants.color',
            'variants.size',
            'tierPrices',
            'ratings'
        ]);

        // Load attribute values with their attribute definitions
        $product->load(['attributeValues' => function ($query) {
            $query->with(['attribute', 'attributeValue']);
        }]);

        // Process highlights - ensure it's an array
        $highlights = [];
        if ($product->highlights) {
            if (is_array($product->highlights)) {
                $highlights = $product->highlights;
            } elseif (is_string($product->highlights)) {
                // Try to decode JSON
                $decoded = json_decode($product->highlights, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $highlights = $decoded;
                } else {
                    // Treat as single string or comma-separated
                    $highlights = array_map('trim', explode(',', $product->highlights));
                }
            }
        }

        // Process attributes - organize by category
        $attributesByCategory = [];
        $allAttributes = [];

        foreach ($product->attributeValues as $attrValue) {
            if ($attrValue->attribute) {
                $attribute = $attrValue->attribute;
                $value = $attrValue->value;

                // Decode JSON values if needed
                if (is_string($value) && $this->isJson($value)) {
                    $value = json_decode($value, true);
                }

                $allAttributes[] = [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'value' => $value,
                    'display_value' => $this->formatAttributeValue($attribute, $value),
                    'category_id' => $attribute->category_id,
                    'category_name' => $attribute->category ? $attribute->category->name : 'General'
                ];

                // Group by category
                $categoryKey = $attribute->category_id ?? 0;
                if (!isset($attributesByCategory[$categoryKey])) {
                    $attributesByCategory[$categoryKey] = [
                        'name' => $attribute->category ? $attribute->category->name : 'General',
                        'attributes' => []
                    ];
                }
                $attributesByCategory[$categoryKey]['attributes'][] = end($allAttributes);
            }
        }

        // Get analytics from daily table
        $totalViews = $product->dailyAnalytics()->sum('views');
        $totalOrders = $product->dailyAnalytics()->sum('orders');
        $totalRevenue = $product->dailyAnalytics()->sum('revenue');
        $cartAdds = $product->dailyAnalytics()->sum('cart_adds');
        $conversionRate = $totalViews > 0 ? ($totalOrders / $totalViews) * 100 : 0;

        $recentAnalytics = $product->dailyAnalytics()
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        // Calculate stock status for display
        $stockStatusText = '';
        $stockStatusClass = '';
        switch ($product->stock_status) {
            case 'instock':
                $stockStatusText = 'In Stock';
                $stockStatusClass = 'success';
                break;
            case 'outofstock':
                $stockStatusText = 'Out of Stock';
                $stockStatusClass = 'danger';
                break;
            case 'backorder':
                $stockStatusText = 'Backorder';
                $stockStatusClass = 'warning';
                break;
        }

        // Calculate discount percentage
        // $discountPercentage = 0;
        // if ($product->compare_price && $product->compare_price > $product->price) {
        //     $discountPercentage = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
        // }

        return view('admin.pages.products.show', compact(
            'product',
            'highlights',
            'allAttributes',
            'attributesByCategory',
            'totalViews',
            'totalOrders',
            'totalRevenue',
            'cartAdds',
            'conversionRate',
            'recentAnalytics',
            'stockStatusText',
            'stockStatusClass',
            // 'discountPercentage'
        ));
    }

    /**
     * Check if string is valid JSON
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Format attribute value for display
     */
    private function formatAttributeValue($attribute, $value)
    {
        if (empty($value)) {
            return 'Not specified';
        }

        switch ($attribute->type) {
            case 'checkbox':
                if (is_array($value)) {
                    return implode(', ', $value);
                }
                return $value;
            case 'multiselect':
                if (is_array($value)) {
                    return implode(', ', $value);
                }
                return $value;
            case 'color':
                return '<span style="display:inline-block;width:20px;height:20px;background:' . $value . ';border:1px solid #ddd;border-radius:4px;"></span> ' . $value;
            case 'date':
                return date('F d, Y', strtotime($value));
            case 'number':
                return number_format($value, 2);
            default:
                return $value;
        }
    }

    /**
     * Show form for editing a product.
     */
    public function edit(Product $product)
    {
        // Load relationships
        $product->load(['categories', 'tags', 'images', 'videos', 'variants', 'attributeValues.attribute', 'tierPrices']);

        // Get parent categories (categories with no parent)
        $parentCategories = Category::active()->whereNull('parent_id')->orderBy('name')->get();

        // Get all categories for other uses
        $categories = Category::active()->orderBy('name')->get();

        // Get brands
        $brands = Brand::active()->orderBy('name')->get();

        // Get colors and sizes for variants
        $colors = Color::active()->orderBy('name')->get();
        $sizes = Size::active()->orderBy('name')->get();

        // Get all attributes (for reference)
        $attributes = Attribute::active()->with('values')->orderBy('order')->get();

        // Get tags
        $tags = Tag::orderBy('name')->get();

        // Get discounts
        $vendors = Vendor::where('is_owner', 1)->get();

        // Get selected tag IDs
        $selectedTags = $product->tags->pluck('id')->toArray();

        // Get all product category IDs
        $allProductCategoryIds = [];
        foreach ($product->categories as $category) {
            $allProductCategoryIds[] = $category->id;
        }

        $primaryCategoryId = $product->primary_category_id;

        // Remove primary category from additional categories
        $selectedAdditionalCategories = array_values(array_diff($allProductCategoryIds, [$primaryCategoryId]));

        // Get existing attribute values for the product
        $existingAttributeValues = [];
        foreach ($product->attributeValues as $attrValue) {
            $existingAttributeValues[$attrValue->attribute_id] = [
                'value' => $attrValue->value,
                'attribute' => $attrValue->attribute
            ];
        }

        // Get additional categories with their names for display
        $additionalCategoriesList = Category::whereIn('id', $selectedAdditionalCategories)->get();

        return view('admin.pages.products.edit', compact(
            'product',
            'parentCategories',
            'categories',
            'brands',
            'colors',
            'sizes',
            'attributes',
            'tags',
            'vendors',
            'selectedTags',
            'selectedAdditionalCategories',
            'existingAttributeValues',
            'additionalCategoriesList'
        ));
    }

    /**
     * Update a product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'brand_id' => 'nullable|exists:brands,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'is_wholesale' => 'boolean',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'is_range' => 'boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_stock' => 'boolean',
            'allow_backorder' => 'boolean',
            'stock_status' => 'required|in:instock,outofstock,backorder',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'free_shipping' => 'boolean',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_new' => 'boolean',
            'sort_order' => 'nullable|integer',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'focus_keyword' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|image|max:2048',
            'primary_category_id' => 'nullable|exists:categories,id',
            'additional_categories' => 'nullable|array',
            'additional_categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'new_tags' => 'nullable|array',
            'new_tags.*' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'images_alt' => 'nullable|array',
            'images_alt.*' => 'nullable|string',
            'existing_images_alt' => 'nullable|array',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'exists:product_images,id',
            'videos' => 'nullable|array',
            'videos.*.url' => 'nullable|url',
            'videos.*.title' => 'nullable|string',
            'existing_videos' => 'nullable|array',
            'remove_videos' => 'nullable|array',
            'new_variants' => 'nullable|array',
            'existing_variants' => 'nullable|array',
            'remove_variants' => 'nullable|array',
            'new_tiers' => 'nullable|array',
            'existing_tiers' => 'nullable|array',
            'remove_tiers' => 'nullable|array',
            'attributes' => 'nullable|array',
            'discounts' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            $oldData = $product->toArray();

            // Prepare product data
            $data = $request->except([
                'images',
                'videos',
                'new_variants',
                'existing_variants',
                'attributes',
                'new_tiers',
                'existing_tiers',
                'discounts',
                'categories',
                'tags',
                'new_tags',
                'og_image',
                'remove_images',
                'remove_videos',
                'remove_variants',
                'remove_tiers',
                'images_alt',
                'existing_images_alt',
                'additional_categories',
                'primary_category_id'
            ]);

            $data['slug'] = $request->slug ?: Str::slug($request->name);
            $data['highlights'] = $request->highlights ? json_encode($request->highlights) : null;

            // Set boolean flags
            $data['is_wholesale'] = $request->has('is_wholesale');
            $data['is_range'] = $request->has('is_range');
            $data['track_stock'] = $request->has('track_stock');
            $data['allow_backorder'] = $request->has('allow_backorder');
            $data['free_shipping'] = $request->has('free_shipping');
            $data['status'] = $request->has('status');
            $data['is_featured'] = $request->has('is_featured');
            $data['is_bestseller'] = $request->has('is_bestseller');
            $data['is_new'] = $request->has('is_new');

            // Set primary_category_id in products table (NOT in pivot)
            if ($request->has('primary_category_id')) {
                $data['primary_category_id'] = $request->primary_category_id;
            }

            $product->update($data);

            // Handle Additional Categories (for product_categories pivot table)
            $allCategoryIds = [];

            // Add additional categories (excluding primary)
            if ($request->has('additional_categories') && is_array($request->additional_categories)) {
                $allCategoryIds = array_merge($allCategoryIds, $request->additional_categories);
            }

            // Remove duplicates
            $allCategoryIds = array_unique($allCategoryIds);

            // Sync to pivot table (only additional categories, NOT including primary)
            $product->categories()->sync($allCategoryIds);

            // Handle Tags - Combine selected tags and new tags
            $allTagIds = [];

            // Get existing tag IDs from select2
            if ($request->has('tags') && is_array($request->tags)) {
                $allTagIds = array_merge($allTagIds, $request->tags);
            }

            // Handle new tags from comma input
            if ($request->has('new_tags') && is_array($request->new_tags)) {
                foreach ($request->new_tags as $newTagName) {
                    if (!empty($newTagName)) {
                        $existingTag = Tag::where('name', $newTagName)->first();
                        if ($existingTag) {
                            $allTagIds[] = $existingTag->id;
                        } else {
                            $newTag = Tag::create([
                                'name' => $newTagName,
                                'slug' => Str::slug($newTagName),
                            ]);
                            $allTagIds[] = $newTag->id;
                        }
                    }
                }
            }

            if (!empty($allTagIds)) {
                $product->tags()->sync($allTagIds);
            }

            // Handle OG image
            if ($request->hasFile('og_image')) {
                if ($product->og_image) {
                    $this->deleteImageIfExists('products/og/' . $product->og_image);
                }
                $compressed = $this->imageCompressor->compress($request->file('og_image'), 'products/og', 1200, 85);
                if ($compressed['success']) {
                    $product->update(['og_image' => $compressed['filename']]);
                }
            }

            // Update existing images alt text
            if ($request->has('existing_images_alt') && is_array($request->existing_images_alt)) {
                foreach ($request->existing_images_alt as $imageId => $altText) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        $image->update(['alt_text' => $altText]);
                    }
                }
            }

            // Handle main image selection
            if ($request->has('main_image_id')) {
                // Reset all images to not main
                ProductImage::where('product_id', $product->id)->update(['is_main' => false]);
                // Set the selected image as main
                $mainImage = ProductImage::find($request->main_image_id);
                if ($mainImage && $mainImage->product_id == $product->id) {
                    $mainImage->update(['is_main' => true]);
                }
            }

            // Remove deleted images
            if ($request->has('remove_images') && is_array($request->remove_images)) {
                foreach ($request->remove_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        $this->deleteImageIfExists('products/' . $image->image);
                        $image->delete();
                    }
                }
            }

            // Add new images
            if ($request->hasFile('images')) {
                $imageFiles = array_values($request->file('images'));
                $altTexts = $request->images_alt ?? [];
                $isMains = $request->new_images_is_main ?? [];
                $currentMaxOrder = $product->images()->max('sort_order') ?? 0;

                foreach ($imageFiles as $index => $imageFile) {
                    $compressed = $this->imageCompressor->compress($imageFile, 'products', 800, 85);
                    if ($compressed['success']) {
                        $image = ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $compressed['filename'],
                            'alt_text' => $altTexts[$index] ?? null,
                            'sort_order' => $currentMaxOrder + $index + 1,
                            'is_main' => isset($isMains[$index]) && $isMains[$index] == '1',
                        ]);

                        // If this image is set as main, reset others
                        if (isset($isMains[$index]) && $isMains[$index] == '1') {
                            ProductImage::where('product_id', $product->id)->where('id', '!=', $image->id)->update(['is_main' => false]);
                        }
                    }
                }
            }

            // Handle videos
            // Remove deleted videos
            if ($request->has('remove_videos') && is_array($request->remove_videos)) {
                $product->videos()->whereIn('id', $request->remove_videos)->delete();
            }

            // Update existing videos
            if ($request->has('existing_videos') && is_array($request->existing_videos)) {
                foreach ($request->existing_videos as $videoId => $videoData) {
                    $video = $product->videos()->find($videoId);
                    if ($video) {
                        $video->update([
                            'url' => $videoData['url'] ?? $video->url,
                            'title' => $videoData['title'] ?? $video->title,
                        ]);
                    }
                }
            }

            // Add new videos
            if ($request->has('new_videos') && is_array($request->new_videos)) {
                foreach ($request->new_videos as $videoData) {
                    if (!empty($videoData['url'])) {
                        $product->videos()->create($videoData);
                    }
                }
            }

            // Handle variants
            // Remove deleted variants
            if ($request->has('remove_variants') && is_array($request->remove_variants)) {
                foreach ($request->remove_variants as $variantId) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant && $variant->product_id == $product->id) {
                        if ($variant->image) {
                            $this->deleteImageIfExists('variants/' . $variant->image);
                        }
                        $variant->delete();
                    }
                }
            }

            // Update existing variants
            if ($request->has('existing_variants') && is_array($request->existing_variants)) {
                foreach ($request->existing_variants as $variantId => $variantData) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant && $variant->product_id == $product->id) {
                        // Handle variant image removal
                        if (isset($variantData['remove_image']) && $variantData['remove_image'] == '1') {
                            if ($variant->image) {
                                $this->deleteImageIfExists('variants/' . $variant->image);
                                $variantData['image'] = null;
                            }
                        }

                        // Handle new variant image
                        if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            if ($variant->image) {
                                $this->deleteImageIfExists('variants/' . $variant->image);
                            }
                            $compressed = $this->imageCompressor->compress($variantData['image'], 'variants', 300, 85);
                            if ($compressed['success']) {
                                $variantData['image'] = $compressed['filename'];
                            }
                        } else {
                            $variantData['image'] = $variant->image;
                        }

                        $variant->update([
                            'color_id' => $variantData['color_id'] ?? $variant->color_id,
                            'size_id' => $variantData['size_id'] ?? $variant->size_id,
                            'sku' => $variantData['sku'] ?? $variant->sku,
                            'price' => $variantData['price'] ?? $variant->price,
                            'compare_price' => $variantData['compare_price'] ?? $variant->compare_price,
                            'wholesale_price' => $variantData['wholesale_price'] ?? $variant->wholesale_price,
                            'stock_quantity' => $variantData['stock_quantity'] ?? $variant->stock_quantity,
                            'image' => $variantData['image'],
                            'image_alt' => $variantData['image_alt'] ?? $variant->image_alt,
                        ]);
                    }
                }
            }

            // Add new variants
            if ($request->has('new_variants') && is_array($request->new_variants)) {
                foreach ($request->new_variants as $variantData) {
                    // Handle variant image if present
                    if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $compressed = $this->imageCompressor->compress($variantData['image'], 'variants', 300, 85);
                        if ($compressed['success']) {
                            $variantData['image'] = $compressed['filename'];
                        }
                    }
                    $variantData['product_id'] = $product->id;
                    ProductVariant::create($variantData);
                }
            }

            // ========== FILTER ATTRIBUTES FROM PAYLOAD ==========
            // Get all selected category IDs (primary + additional)
            $allSelectedCategoryIds = [];

            // Add primary category
            if ($request->has('primary_category_id') && $request->primary_category_id) {
                $allSelectedCategoryIds[] = $request->primary_category_id;
            }

            // Add additional categories
            if ($request->has('additional_categories') && is_array($request->additional_categories)) {
                $allSelectedCategoryIds = array_merge($allSelectedCategoryIds, $request->additional_categories);
            }
            $allSelectedCategoryIds = array_unique($allSelectedCategoryIds);

            // 2. Get valid attribute-category pairs from attribute_category table
            $validAttributeCategoryPairs = \DB::table('attribute_category')
                ->whereIn('category_id', $allSelectedCategoryIds)
                ->get(['attribute_id', 'category_id'])
                ->toArray();

            // Create an array of valid attribute IDs for quick lookup
            $validAttributeIds = array_unique(array_column($validAttributeCategoryPairs, 'attribute_id'));

            // 3. Delete all existing attribute values for this product first
            $product->attributeValues()->delete();

            // 4. Process payload attributes - only insert if they exist in attribute_category table
            if ($request->has('attributes')) {
                $attributes = $request->input('attributes');

                if (is_array($attributes) && !empty($attributes)) {
                    foreach ($attributes as $attrId => $value) {
                        // Skip empty values
                        if ($value === null || $value === '') {
                            continue;
                        }

                        // Check if this attribute_id is valid for any of the selected categories
                        if (!in_array($attrId, $validAttributeIds)) {
                            continue; // Skip - this attribute doesn't belong to selected categories
                        }

                        $attributeValueId = null;
                        $savedValue = $value;

                        // Check if this attribute has predefined values in attribute_values table
                        $attribute = Attribute::find($attrId);
                        if ($attribute && $attribute->values()->exists()) {
                            // Find the attribute_value_id that matches the selected value
                            $attributeValue = $attribute->values()
                                ->where('value', $value)
                                ->orWhere('label', $value)
                                ->first();

                            if ($attributeValue) {
                                $attributeValueId = $attributeValue->id;
                                $savedValue = $attributeValue->value;
                            }
                        }

                        // Handle array values (for multiselect)
                        if (is_array($value)) {
                            $savedValue = json_encode($value);
                        }

                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attrId,
                            'attribute_value_id' => $attributeValueId,
                            'value' => $savedValue,
                        ]);
                    }
                }
            }

            // Handle tier prices
            // Remove deleted tiers
            if ($request->has('remove_tiers') && is_array($request->remove_tiers)) {
                ProductTierPrice::whereIn('id', $request->remove_tiers)->where('product_id', $product->id)->delete();
            }

            // Update existing tiers
            if ($request->has('existing_tiers') && is_array($request->existing_tiers)) {
                foreach ($request->existing_tiers as $tierId => $tierData) {
                    $tier = ProductTierPrice::find($tierId);
                    if ($tier && $tier->product_id == $product->id) {
                        $tier->update([
                            'min_quantity' => $tierData['min_quantity'] ?? $tier->min_quantity,
                            'max_quantity' => $tierData['max_quantity'] ?? $tier->max_quantity,
                            'price' => $tierData['price'] ?? $tier->price,
                        ]);
                    }
                }
            }

            // Add new tiers
            if ($request->has('new_tiers') && is_array($request->new_tiers)) {
                foreach ($request->new_tiers as $tierData) {
                    if (!empty($tierData['min_quantity']) && !empty($tierData['price'])) {
                        $tierData['product_id'] = $product->id;
                        ProductTierPrice::create($tierData);
                    }
                }
            }

            DB::commit();

            $this->logActivity(
                'update',
                'product',
                'admin',
                $product->id,
                $product->name,
                $oldData,
                $product->toArray(),
                "Updated product: {$product->name}"
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully.',
                    'product' => $product
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update product: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to update product: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Delete a product.
     */
    public function destroy(Product $product)
    {
        // Delete all associated images from storage
        foreach ($product->images as $image) {
            $this->deleteImageIfExists('products/' . $image->image);
        }
        foreach ($product->videos as $video) {
            // if video thumbnail is stored locally, delete it
        }
        $this->deleteImageIfExists('products/og/' . $product->og_image);

        $productName = $product->name;
        $product->delete();

        $this->logActivity(
            'delete',
            'product',
            'admin',
            $product->id,
            $productName,
            null,
            null,
            "Deleted product: {$productName}"
        );

        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }

    /**
     * Toggle product status (active/inactive).
     */
    public function toggleStatus(Product $product)
    {
        $oldStatus = $product->status;
        $product->update(['status' => !$product->status]);

        $this->logActivity(
            'toggle_status',
            'product',
            'admin',
            $product->id,
            $product->name,
            ['status' => $oldStatus],
            ['status' => $product->status],
            "Toggled product status for '{$product->name}' to " . ($product->status ? 'Active' : 'Inactive')
        );

        return response()->json(['success' => true, 'message' => 'Product status updated.', 'status' => $product->status]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return response()->json(['success' => true, 'message' => 'Featured status updated.', 'is_featured' => $product->is_featured]);
    }

    /**
     * Approve a vendor-submitted product.
     */
    public function approveProduct(Product $product)
    {
        if ($product->approval_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Product is not pending approval.'], 422);
        }

        $oldStatus = $product->approval_status;
        $product->update([
            'approval_status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'status' => true,
        ]);

        $this->logActivity(
            'approve',
            'product',
            'admin',
            $product->id,
            $product->name,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'approved'],
            "Approved product: {$product->name}"
        );

        return response()->json(['success' => true, 'message' => 'Product approved successfully.']);
    }

    /**
     * Reject a vendor-submitted product.
     */
    public function rejectProduct(Request $request, Product $product)
    {
        if ($product->approval_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Product is not pending approval.'], 422);
        }

        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $oldStatus = $product->approval_status;
        $product->update([
            'approval_status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'status' => false,
        ]);

        $this->logActivity(
            'reject',
            'product',
            'admin',
            $product->id,
            $product->name,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'rejected', 'rejection_reason' => $request->rejection_reason],
            "Rejected product: {$product->name} - Reason: {$request->rejection_reason}"
        );

        return response()->json(['success' => true, 'message' => 'Product rejected successfully.']);
    }

    /**
     * Bulk action on products.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,approve,reject',
            'product_ids' => 'required|string',
        ]);

        $action = $request->action;
        $productIds = json_decode($request->product_ids);
        $products = Product::whereIn('id', $productIds)->get();
        $count = 0;
        $errors = [];

        foreach ($products as $product) {
            try {
                switch ($action) {
                    case 'activate':
                        $product->update(['status' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $product->update(['status' => false]);
                        $count++;
                        break;
                    case 'feature':
                        $product->update(['is_featured' => true]);
                        $count++;
                        break;
                    case 'unfeature':
                        $product->update(['is_featured' => false]);
                        $count++;
                        break;
                    case 'approve':
                        if ($product->approval_status === 'pending') {
                            $product->update(['approval_status' => 'approved', 'approved_by' => auth('admin')->id(), 'approved_at' => now(), 'status' => true]);
                            $count++;
                        }
                        break;
                    case 'reject':
                        if ($product->approval_status === 'pending') {
                            $product->update(['approval_status' => 'rejected', 'approved_by' => auth('admin')->id(), 'approved_at' => now(), 'status' => false]);
                            $count++;
                        }
                        break;
                    case 'delete':
                        // Delete images from storage
                        foreach ($product->images as $image) {
                            $this->deleteImageIfExists('products/' . $image->image);
                        }
                        $product->delete();
                        $count++;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$product->name}': " . $e->getMessage();
            }
        }

        $message = "{$count} products processed successfully.";
        if (!empty($errors)) {
            $message .= ' Errors: ' . implode(' ', $errors);
        }

        return response()->json(['success' => true, 'message' => $message, 'count' => $count, 'errors' => $errors]);
    }

    /**
     * Product analytics dashboard for vendor
     */
    public function analytics(Request $request)
    {
        // Date range
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Get products with analytics
        $products = Product::with(['images' => function ($q) {
            $q->where('is_main', true);
        }])
            ->get();

        // Calculate totals from daily analytics
        $totalViews = 0;
        $totalOrders = 0;
        $totalRevenue = 0;

        foreach ($products as $product) {
            $product->total_views = $product->dailyAnalytics()->whereBetween('date', [$startDate, $endDate])->sum('views');
            $product->total_orders = $product->dailyAnalytics()->whereBetween('date', [$startDate, $endDate])->sum('orders');
            $product->total_revenue = $product->dailyAnalytics()->whereBetween('date', [$startDate, $endDate])->sum('revenue');

            $totalViews += $product->total_views;
            $totalOrders += $product->total_orders;
            $totalRevenue += $product->total_revenue;
        }

        // Top products by revenue
        $topRevenueProducts = $products->sortByDesc('total_revenue')->take(10);

        // Top products by views
        $topViewsProducts = $products->sortByDesc('total_views')->take(10);

        // Top products by orders
        $topOrdersProducts = $products->sortByDesc('total_orders')->take(10);

        // Low stock products
        $lowStockProducts = Product::where('track_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->get();

        // Statistics
        $totalProducts = $products->count();
        $activeProducts = Product::where('status', true)->count();
        $pendingApproval = Product::where('approval_status', 'pending')->count();
        $lowStockCount = $lowStockProducts->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Averages
        $avgViewsPerProduct = $totalProducts > 0 ? round($totalViews / $totalProducts, 0) : 0;
        $avgRevenuePerProduct = $totalProducts > 0 ? $totalRevenue / $totalProducts : 0;
        $avgOrdersPerProduct = $totalProducts > 0 ? round($totalOrders / $totalProducts, 0) : 0;
        $conversionRate = $totalViews > 0 ? ($totalOrders / $totalViews) * 100 : 0;

        // Chart data (last 30 days)
        $dailyData = ProductDailyAnalytic::whereIn('product_id', $products->pluck('id'))
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('date');

        $chartLabels = [];
        $chartViewsData = [];
        $chartOrdersData = [];
        $chartRevenueData = [];

        $period = now()->parse($startDate)->diffInDays(now()->parse($endDate));
        for ($i = 0; $i <= $period; $i++) {
            $date = now()->parse($startDate)->addDays($i)->format('Y-m-d');
            $chartLabels[] = now()->parse($startDate)->addDays($i)->format('M d');

            $dayData = $dailyData->get($date, collect());
            $chartViewsData[] = $dayData->sum('views');
            $chartOrdersData[] = $dayData->sum('orders');
            $chartRevenueData[] = $dayData->sum('revenue');
        }

        return view('admin.pages.products.analytics', compact(
            'topRevenueProducts',
            'topViewsProducts',
            'topOrdersProducts',
            'lowStockProducts',
            'totalProducts',
            'activeProducts',
            'totalViews',
            'totalOrders',
            'totalRevenue',
            'pendingApproval',
            'lowStockCount',
            'avgOrderValue',
            'avgViewsPerProduct',
            'avgRevenuePerProduct',
            'avgOrdersPerProduct',
            'conversionRate',
            'chartLabels',
            'chartViewsData',
            'chartOrdersData',
            'chartRevenueData',
            'startDate',
            'endDate'
        ));
    }

    public function exportLowStock()
    {
        $lowStock = Product::where('track_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->where('stock_quantity', '>', 0)
            ->get();

        $filename = 'low-stock-products-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($lowStock) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['ID', 'Name', 'SKU', 'Current Stock', 'Low Stock Threshold', 'Status']);

            // Add data
            foreach ($lowStock as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->sku ?? 'N/A',
                    $product->stock_quantity,
                    $product->low_stock_threshold,
                    $product->status ? 'Active' : 'Inactive'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper: delete image from storage.
     */
    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

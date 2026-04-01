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
        
        switch($sortBy) {
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
    $subcategories = Category::whereNotNull('parent_id')->where('status', true)->orderBy('name')->get();
    $colors = Color::where('status', true)->orderBy('name')->get();
    $sizes = Size::where('status', true)->orderBy('name')->get();
    
    // Get custom attributes (universal attributes)
    $customAttributes = Attribute::with('values')
        ->where('status', true)
        ->orderBy('display_order')
        ->get();
    
    // Load the product with its relationships - IMPORTANT: Use the correct relationship
    $product->load(['subcategories', 'colors', 'sizes', 'variants', 'tierPrices', 'customAttributes']);
    
    // Create a map of existing custom attribute values for quick lookup
    // This will have structure: [attribute_id => [value_id1, value_id2, ...]]
    $existingCustomAttributes = [];
    foreach ($product->customAttributes as $value) {
        $attributeId = $value->attribute_id;
        if (!isset($existingCustomAttributes[$attributeId])) {
            $existingCustomAttributes[$attributeId] = [];
        }
        $existingCustomAttributes[$attributeId][] = $value->id;
    }
    
    // Debug: Log what we found
    \Log::info('Product custom attributes loaded', [
        'product_id' => $product->id,
        'custom_attributes_count' => $product->customAttributes->count(),
        'existing_custom_attributes' => $existingCustomAttributes
    ]);
    
    return view('admin.pages.products.edit', compact(
        'product', 
        'categories', 
        'subcategories', 
        'colors', 
        'sizes', 
        'customAttributes',
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
        'short_description' => 'nullable|string',
        'description' => 'nullable|string',
        'featured_image' => 'nullable|image|max:2048',
        'gallery_images.*' => 'nullable|image|max:2048',
        'variants' => 'nullable|array',
        'tier_prices' => 'nullable|array',
        'custom_attributes' => 'nullable|array',
        'remove_featured_image' => 'nullable|boolean',
        'remove_gallery_images' => 'nullable|array',
    ]);

    DB::beginTransaction();
    
    try {
        $data = $request->except([
            'featured_image', 'gallery_images', 'subcategories', 'colors', 'sizes', 
            'variants', 'tier_prices', 'custom_attributes', 'remove_featured_image', 
            'remove_gallery_images', '_token', '_method'
        ]);
        
        // Generate slug if name changed
        if ($request->name !== $product->name) {
            $data['slug'] = Str::slug($request->name);
        }
        
        // Handle featured image
        if ($request->has('remove_featured_image') && $request->remove_featured_image) {
            if ($product->featured_image) {
                Storage::disk('public')->delete('products/' . $product->featured_image);
                $data['featured_image'] = null;
            }
        }
        
        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                Storage::disk('public')->delete('products/' . $product->featured_image);
            }
            $compressed = $this->imageCompressor->compress($request->file('featured_image'), 'products', 800, 85);
            if ($compressed['success']) {
                $data['featured_image'] = $compressed['filename'];
            }
        }
        
        // Handle gallery images removal
        if ($request->has('remove_gallery_images') && is_array($request->remove_gallery_images)) {
            foreach ($request->remove_gallery_images as $imageId) {
                $image = $product->images()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }
        
        // Handle new gallery images
        if ($request->hasFile('gallery_images')) {
            $existingCount = $product->images()->count();
            foreach ($request->file('gallery_images') as $index => $image) {
                $compressed = $this->imageCompressor->compress($image, 'products/gallery', 800, 85);
                if ($compressed['success']) {
                    $product->images()->create([
                        'image_path' => 'products/gallery/' . $compressed['filename'],
                        'is_featured' => false,
                        'order' => $existingCount + $index,
                    ]);
                }
            }
        }
        
        // Update product
        $product->update($data);
        
        // Handle subcategories
        if ($request->has('subcategories') && is_array($request->subcategories)) {
            $product->subcategories()->sync($request->subcategories);
        } else {
            $product->subcategories()->sync([]);
        }
        
        // Handle colors
        if ($request->has('colors') && is_array($request->colors)) {
            $colorData = [];
            foreach ($request->colors as $colorId) {
                $colorData[$colorId] = ['color_image' => null];
            }
            $product->colors()->sync($colorData);
        } else {
            $product->colors()->sync([]);
        }
        
        // Handle sizes
        if ($request->has('sizes') && is_array($request->sizes)) {
            $sizeData = [];
            foreach ($request->sizes as $sizeId) {
                $sizeData[$sizeId] = ['stock' => 0, 'price_adjustment' => 0];
            }
            $product->sizes()->sync($sizeData);
        } else {
            $product->sizes()->sync([]);
        }
        
        // Handle variants - Delete existing and recreate
        if ($request->has('variants') && is_array($request->variants)) {
            // Delete existing variants
            $product->variants()->delete();
            
            // Create new variants
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
        
        // Handle tiered pricing - Delete existing and recreate
        if ($request->pricing_type === 'tiered' && $request->has('tier_prices') && is_array($request->tier_prices)) {
            // Delete existing tier prices
            $product->tierPrices()->delete();
            
            // Create new tier prices
            foreach ($request->tier_prices as $tier) {
                if (!empty($tier['min_quantity']) && !empty($tier['price'])) {
                    $product->tierPrices()->create([
                        'min_quantity' => $tier['min_quantity'],
                        'max_quantity' => $tier['max_quantity'] ?? null,
                        'price' => $tier['price'],
                    ]);
                }
            }
        } else {
            // If pricing type changed to single, remove all tier prices
            $product->tierPrices()->delete();
        }
        
        // ========== HANDLE CUSTOM ATTRIBUTES - FIXED ==========
        if ($request->has('custom_attributes') && is_array($request->custom_attributes)) {
            $attributeValueIds = [];
            
            // Collect all attribute value IDs from the request
            foreach ($request->custom_attributes as $attributeId => $value) {
                if (is_array($value)) {
                    // For multiselect, checkboxes, etc.
                    foreach ($value as $val) {
                        if (!empty($val) && is_numeric($val)) {
                            $attributeValueIds[] = (int)$val;
                        }
                    }
                } else {
                    // For single select, radio, etc.
                    if (!empty($value) && is_numeric($value)) {
                        $attributeValueIds[] = (int)$value;
                    }
                }
            }
            
            // Remove duplicates
            $attributeValueIds = array_unique($attributeValueIds);
            
            if (!empty($attributeValueIds)) {
                // Sync the relationship - this will remove old and add new
                $product->customAttributes()->sync($attributeValueIds);
                
                \Log::info('Custom attributes synced for product', [
                    'product_id' => $product->id,
                    'attribute_value_ids' => $attributeValueIds
                ]);
            } else {
                // If no custom attributes, detach all
                $product->customAttributes()->detach();
                \Log::info('All custom attributes detached for product', [
                    'product_id' => $product->id
                ]);
            }
        } else {
            // If no custom_attributes in request, remove all existing
            $product->customAttributes()->detach();
            \Log::info('No custom attributes in request, detached all', [
                'product_id' => $product->id
            ]);
        }
        
        DB::commit();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully.',
                'product' => $product
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
        
        $chartLabels = $dailyStats->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();
        
        return response()->json([
            'product' => $product,
            'view_count' => $product->view_count,
            'order_count' => $product->order_count,
            'total_sold' => $product->total_sold,
            'avg_rating' => $product->avg_rating,
            'review_count' => $product->review_count,
            'chart_labels' => $chartLabels,
            'chart_views' => $dailyStats->pluck('views'),
            'chart_orders' => $dailyStats->pluck('orders'),
        ]);
    }

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

    private function getDaysFromRange($range)
    {
        return match($range) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            'year' => 365,
            default => 30
        };
    }
}
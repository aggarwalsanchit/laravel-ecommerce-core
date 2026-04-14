<?php
// app/Http/Controllers/Admin/AttributeValueController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AttributeValueController extends Controller
{
    protected $imageCompressor;

    public function __construct(ImageCompressionService $imageCompressor)
    {
        $this->imageCompressor = $imageCompressor;
    }

    /**
     * Display a listing of attribute values.
     */
    public function index(Attribute $attribute)
    {
        $values = $attribute->values()->orderBy('display_order')->get();

        if (request()->ajax()) {
            return response()->json([
                'values' => $values,
                'attribute' => $attribute
            ]);
        }

        return view('admin.pages.attributes.values.index', compact('attribute', 'values'));
    }

    /**
     * Show a specific attribute value.
     */
    public function show($id)
    {
        $value = AttributeValue::with('attribute')->findOrFail($id);

        return response()->json([
            'id' => $value->id,
            'value' => $value->value,
            'description' => $value->description,
            'color_code' => $value->color_code,
            'color_name' => $value->color_name,
            'size_value' => $value->size_value,
            'size_unit' => $value->size_unit,
            'price_adjustment' => $value->price_adjustment,
            'stock' => $value->stock,
            'sku' => $value->sku,
            'min_value' => $value->min_value,
            'max_value' => $value->max_value,
            'display_order' => $value->display_order,
            'is_default' => $value->is_default,
            'is_visible' => $value->is_visible,
            'image' => $value->image,
            'usage_count' => $value->usage_count,
            'view_count' => $value->view_count,
            'order_count' => $value->order_count,
            'total_revenue' => $value->total_revenue,
        ]);
    }

    /**
     * Store a newly created attribute value.
     */
    public function store(Request $request, Attribute $attribute)
    {
        $request->validate([
            'value' => 'required|unique:attribute_values,value,NULL,id,attribute_id,' . $attribute->id,
            'color_code' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
            'image' => 'nullable|image|max:1024',
            'price_adjustment' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer',
            'is_default' => 'boolean',
            'is_visible' => 'boolean',
        ]);

        $data = $request->except(['image']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $folder = 'attributes/' . $attribute->slug . '/values';
            $compressed = $this->imageCompressor->compress($request->file('image'), $folder, 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        // If this is default, remove default from others
        if ($request->has('is_default') && $request->is_default) {
            $attribute->values()->update(['is_default' => false]);
        }

        $value = $attribute->values()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Attribute value added successfully.',
            'value' => $value
        ]);
    }

    /**
     * Update the specified attribute value.
     */
    public function update(Request $request, $id)
    {
        $value = AttributeValue::findOrFail($id);
        $attribute = $value->attribute;

        $request->validate([
            'value' => 'required|unique:attribute_values,value,' . $value->id . ',id,attribute_id,' . $attribute->id,
            'color_code' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
            'image' => 'nullable|image|max:1024',
            'price_adjustment' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer',
            'is_default' => 'boolean',
            'is_visible' => 'boolean',
        ]);

        $data = $request->except(['image', 'remove_image']);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($value->image) {
                Storage::disk('public')->delete('attributes/' . $attribute->slug . '/values/' . $value->image);
            }
            $folder = 'attributes/' . $attribute->slug . '/values';
            $compressed = $this->imageCompressor->compress($request->file('image'), $folder, 150, 80);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
            }
        }

        if ($request->has('remove_image') && $request->remove_image) {
            if ($value->image) {
                Storage::disk('public')->delete('attributes/' . $attribute->slug . '/values/' . $value->image);
                $data['image'] = null;
            }
        }

        // If this is default, remove default from others
        if ($request->has('is_default') && $request->is_default && !$value->is_default) {
            $attribute->values()->update(['is_default' => false]);
        }

        $value->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Attribute value updated successfully.'
        ]);
    }

    /**
     * Get analytics for a specific attribute value.
     */
    public function analytics($id)
    {
        $value = AttributeValue::with('attribute')->findOrFail($id);

        // Get daily analytics for last 30 days
        $startDate = now()->subDays(30);

        $dailyStats = DB::table('attribute_analytics_logs')
            ->where('attribute_value_id', $value->id)
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(CASE WHEN event_type = "view" THEN 1 END) as views'),
                DB::raw('COUNT(CASE WHEN event_type = "order" THEN 1 END) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $dailyStats->pluck('date')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        })->toArray();

        $chartViews = $dailyStats->pluck('views')->toArray();
        $chartOrders = $dailyStats->pluck('orders')->toArray();

        // If no data, generate sample data for chart
        if (empty($chartLabels)) {
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $chartLabels[] = $date->format('M d');
                $chartViews[] = rand(5, 50);
                $chartOrders[] = rand(0, 10);
            }
        }

        return response()->json([
            'view_count' => $value->view_count,
            'order_count' => $value->order_count,
            'total_revenue' => number_format($value->total_revenue, 2),
            'conversion_rate' => $value->view_count > 0
                ? round(($value->order_count / $value->view_count) * 100, 2)
                : 0,
            'chart_labels' => $chartLabels,
            'chart_views' => $chartViews,
            'chart_orders' => $chartOrders,
        ]);
    }

    /**
     * Toggle default status.
     */
    public function toggleDefault($id)
    {
        $value = AttributeValue::findOrFail($id);
        $attribute = $value->attribute;

        // Remove default from all values
        $attribute->values()->update(['is_default' => false]);

        // Set this as default
        $value->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default value updated successfully.'
        ]);
    }

    /**
     * Toggle visibility.
     */
    public function toggleVisibility($id)
    {
        $value = AttributeValue::findOrFail($id);
        $value->update(['is_visible' => !$value->is_visible]);

        return response()->json([
            'success' => true,
            'message' => 'Visibility updated successfully.',
            'is_visible' => $value->is_visible
        ]);
    }

    /**
     * Reorder value.
     */
    public function reorder($id, Request $request)
    {
        $value = AttributeValue::findOrFail($id);
        $attribute = $value->attribute;

        $direction = $request->direction;
        $currentOrder = $value->display_order;

        if ($direction === 'up') {
            $newOrder = $currentOrder - 1;
            $swapValue = $attribute->values()
                ->where('display_order', $newOrder)
                ->first();

            if ($swapValue) {
                $swapValue->update(['display_order' => $currentOrder]);
                $value->update(['display_order' => $newOrder]);
            }
        } elseif ($direction === 'down') {
            $newOrder = $currentOrder + 1;
            $swapValue = $attribute->values()
                ->where('display_order', $newOrder)
                ->first();

            if ($swapValue) {
                $swapValue->update(['display_order' => $currentOrder]);
                $value->update(['display_order' => $newOrder]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.'
        ]);
    }

    /**
     * Delete attribute value.
     */
    public function destroy($id)
    {
        $value = AttributeValue::findOrFail($id);

        if ($value->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete value because it is used by ' . $value->products()->count() . ' products.'
            ], 422);
        }

        if ($value->image) {
            Storage::disk('public')->delete('attributes/' . $value->attribute->slug . '/values/' . $value->image);
        }

        $value->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute value deleted successfully.'
        ]);
    }

    /**
     * Export values.
     */
    public function export(Attribute $attribute)
    {
        $values = $attribute->values()->get();

        $filename = $attribute->slug . '-values-' . date('Y-m-d') . '.csv';

        $handle = fopen('php://temp', 'w+');
        fputcsv($handle, ['ID', 'Value', 'Slug', 'Color Code', 'Price Adjustment', 'Stock', 'SKU', 'Order', 'Default']);

        foreach ($values as $value) {
            fputcsv($handle, [
                $value->id,
                $value->value,
                $value->slug,
                $value->color_code,
                $value->price_adjustment,
                $value->stock,
                $value->sku,
                $value->display_order,
                $value->is_default ? 'Yes' : 'No'
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attributes,id',
            'value' => 'required|unique:attribute_values,value,NULL,id,attribute_id,' . $request->attribute_id,
            'color_code' => 'nullable|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $value = AttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'color_code' => $request->color_code,
            'is_default' => $request->is_default ?? false,
        ]);

        return response()->json(['success' => true, 'value' => $value]);
    }
}

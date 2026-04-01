<?php
// app/Http/Controllers/Admin/AttributeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Category;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class AttributeController extends Controller implements HasMiddleware
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
            new Middleware('permission:view attributes', only: ['index', 'show', 'analytics']),
            new Middleware('permission:create attributes', only: ['create', 'store']),
            new Middleware('permission:edit attributes', only: ['edit', 'update']),
            new Middleware('permission:delete attributes', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Attribute::with('group', 'category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        if ($request->filled('attribute_group_id')) {
            $query->where('attribute_group_id', $request->attribute_group_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $attributes = $query->orderBy('display_order')->paginate(15);

        $groups = AttributeGroup::where('status', true)->get();

        if ($request->ajax()) {
            $table = view('admin.pages.attributes.partials.table', compact('attributes'))->render();
            $pagination = $attributes->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.attributes.index', compact('attributes', 'groups'));
    }

    public function create()
    {
        $groups = AttributeGroup::where('status', true)->orderBy('display_order')->get();
        $categories = Category::where('status', true)->orderBy('name')->get();
        return view('admin.pages.attributes.create', compact('groups', 'categories'));
    }

    public function store(Request $request)
    {
        // Prevent creating duplicate core attributes
        $coreAttributes = ['Color', 'Size', 'Category'];
        if (in_array($request->name, $coreAttributes)) {
            return response()->json([
                'success' => false,
                'message' => "{$request->name} is a core attribute. Please use the dedicated section."
            ], 422);
        }

        $request->validate([
            'name' => 'required|unique:attributes,name|max:255',
            'code' => 'nullable|unique:attributes,code|max:50',
            'type' => 'required|in:text,textarea,number,select,multiselect,color,size,checkbox,radio,date,datetime,boolean,range',
            'unit' => 'nullable|string|max:20',
            'attribute_group_id' => 'nullable|exists:attribute_groups,id',
            'attribute_category_id' => 'nullable|exists:attribute_categories,id',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_variant' => 'boolean',
            'has_image' => 'boolean',
            'discount_applicable' => 'boolean',
            'track_analytics' => 'boolean',
            'status' => 'boolean',
            'icon' => 'nullable|image|max:512',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['icon']);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('attributes/icons', 'public');
            $data['icon'] = basename($iconPath);
        }

        $attribute = Attribute::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute created successfully.',
                'attribute' => $attribute
            ]);
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    public function show(Attribute $attribute)
    {
        $attribute->load('values', 'group', 'category');
        return view('admin.pages.attributes.show', compact('attribute'));
    }

    public function edit(Attribute $attribute)
    {
        $groups = AttributeGroup::where('status', true)->orderBy('display_order')->get();
        $categories = Category::where('status', true)->orderBy('name')->get();
        return view('admin.pages.attributes.edit', compact('attribute', 'groups', 'categories'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name,' . $attribute->id . '|max:255',
            'code' => 'nullable|unique:attributes,code,' . $attribute->id . '|max:50',
            'type' => 'required|in:text,textarea,number,select,multiselect,color,size,checkbox,radio,date,datetime,boolean,range',
            'unit' => 'nullable|string|max:20',
            'attribute_group_id' => 'nullable|exists:attribute_groups,id',
            'attribute_category_id' => 'nullable|exists:attribute_categories,id',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_variant' => 'boolean',
            'has_image' => 'boolean',
            'discount_applicable' => 'boolean',
            'track_analytics' => 'boolean',
            'status' => 'boolean',
            'icon' => 'nullable|image|max:512',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['icon', 'remove_icon']);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            if ($attribute->icon) {
                Storage::disk('public')->delete('attributes/icons/' . $attribute->icon);
            }
            $iconPath = $request->file('icon')->store('attributes/icons', 'public');
            $data['icon'] = basename($iconPath);
        }

        if ($request->has('remove_icon') && $request->remove_icon) {
            if ($attribute->icon) {
                Storage::disk('public')->delete('attributes/icons/' . $attribute->icon);
                $data['icon'] = null;
            }
        }

        $attribute->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute updated successfully.'
            ]);
        }

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        if ($attribute->values()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete attribute because it has ' . $attribute->values()->count() . ' values.'
            ], 422);
        }

        if ($attribute->icon) {
            Storage::disk('public')->delete('attributes/icons/' . $attribute->icon);
        }

        $attribute->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute deleted successfully.'
        ]);
    }

    public function analytics(Attribute $attribute, Request $request)
    {
        $dateRange = $request->get('date_range', '30days');
        $startDate = now()->subDays($this->getDaysFromRange($dateRange));

        $values = $attribute->values()
            ->withCount(['products as product_count'])
            ->orderBy('order_count', 'desc')
            ->get();

        $logs = \DB::table('attribute_analytics_logs')
            ->where('attribute_id', $attribute->id)
            ->where('created_at', '>=', $startDate)
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('COUNT(CASE WHEN event_type = "view" THEN 1 END) as views'),
                \DB::raw('COUNT(CASE WHEN event_type = "click" THEN 1 END) as clicks'),
                \DB::raw('COUNT(CASE WHEN event_type = "order" THEN 1 END) as orders'),
                \DB::raw('SUM(CASE WHEN event_type = "order" THEN revenue ELSE 0 END) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'attribute' => $attribute,
            'values' => $values,
            'total_views' => $attribute->total_views,
            'total_clicks' => $attribute->total_clicks,
            'total_revenue' => $attribute->total_revenue,
            'chart_labels' => $logs->pluck('date'),
            'chart_views' => $logs->pluck('views'),
            'chart_orders' => $logs->pluck('orders'),
        ]);
    }

    private function getDaysFromRange($range)
    {
        return match ($range) {
            '7days' => 7,
            '30days' => 30,
            '90days' => 90,
            'year' => 365,
            default => 30
        };
    }
}

<?php
// app/Http/Controllers/Admin/AttributeGroupController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttributeGroupController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view attributegroups', only: ['index', 'show']),
            new Middleware('permission:create attributegroups', only: ['create', 'store']),
            new Middleware('permission:edit attributegroups', only: ['edit', 'update']),
            new Middleware('permission:delete attributegroups', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = AttributeGroup::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $groups = $query->orderBy('display_order')->paginate(15);

        if ($request->ajax()) {
            $table = view('admin.pages.attributes.groups.partials.table', compact('groups'))->render();
            $pagination = $groups->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination
            ]);
        }

        return view('admin.pages.attributes.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.pages.attributes.groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attribute_groups,name|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'display_order' => 'nullable|integer',
            'is_collapsible' => 'boolean',
            'is_collapsed_by_default' => 'boolean',
            'show_in_sidebar' => 'boolean',
            'show_in_compare' => 'boolean',
            'status' => 'boolean',
        ]);

        $group = AttributeGroup::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute group created successfully.',
                'group' => $group
            ]);
        }

        return redirect()->route('admin.attribute-groups.index')
            ->with('success', 'Attribute group created successfully.');
    }

    public function show(AttributeGroup $attributeGroup)
    {
        $attributeGroup->load('attributes');
        return view('admin.pages.attributes.groups.show', compact('attributeGroup'));
    }

    public function edit(AttributeGroup $attributeGroup)
    {
        return view('admin.pages.attributes.groups.edit', compact('attributeGroup'));
    }

    public function update(Request $request, AttributeGroup $attributeGroup)
    {
        $request->validate([
            'name' => 'required|unique:attribute_groups,name,' . $attributeGroup->id . '|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'display_order' => 'nullable|integer',
            'is_collapsible' => 'boolean',
            'is_collapsed_by_default' => 'boolean',
            'show_in_sidebar' => 'boolean',
            'show_in_compare' => 'boolean',
            'status' => 'boolean',
        ]);

        $attributeGroup->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute group updated successfully.'
            ]);
        }

        return redirect()->route('admin.attribute-groups.index')
            ->with('success', 'Attribute group updated successfully.');
    }

    public function destroy(AttributeGroup $attributeGroup)
    {
        if ($attributeGroup->attributes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete group because it has ' . $attributeGroup->attributes()->count() . ' attributes.'
            ], 422);
        }

        $attributeGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attribute group deleted successfully.'
        ]);
    }

    public function toggleStatus(AttributeGroup $attributeGroup)
    {
        $attributeGroup->update(['status' => !$attributeGroup->status]);
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'status' => $attributeGroup->status
        ]);
    }
}

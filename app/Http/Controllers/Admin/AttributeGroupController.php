<?php
// app/Http/Controllers/Admin/AttributeGroupController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttributeGroup;
use App\Models\Attribute;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AttributeGroupController extends Controller implements HasMiddleware
{
    use LogsAdminActivity;

    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_attribute_groups', only: ['index', 'show', 'analytics', 'pendingRequests', 'viewRequest']),
            new Middleware('permission:create_attribute_groups', only: ['create', 'store']),
            new Middleware('permission:edit_attribute_groups', only: ['edit', 'update', 'toggleStatus', 'bulkAction']),
            new Middleware('permission:approve_attribute_groups', only: ['approveRequest', 'rejectRequest']),
            new Middleware('permission:delete_attribute_groups', only: ['destroy', 'deleteRequest']),
        ];
    }

    /**
     * Display a listing of attribute groups
     */
    public function index(Request $request)
    {
        $query = AttributeGroup::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $groups = $query->paginate(15);

        $statistics = [
            'total' => AttributeGroup::count(),
            'active' => AttributeGroup::where('status', true)->count(),
            'pending' => AttributeGroup::where('approval_status', 'pending')->count(),
            'rejected' => AttributeGroup::where('approval_status', 'rejected')->count(),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.attribute-groups.partials.groups-table', compact('groups'))->render();
            $pagination = $groups->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.attribute-groups.index', compact('groups', 'statistics'));
    }

    /**
     * Show form for creating new attribute group
     */
    public function create()
    {
        return view('admin.pages.attribute-groups.create');
    }

    /**
     * Store newly created attribute group
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attribute_groups,name',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_collapsible' => 'boolean',
            'is_open_by_default' => 'boolean',
            'icon' => 'nullable|string|max:100',
            'position' => 'nullable|in:top,sidebar,bottom',
            'status' => 'boolean',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['approval_status'] = 'approved';

        $group = AttributeGroup::create($data);

        $this->logActivity(
            'create',
            'attribute_group',
            'admin',
            $group->id,
            $group->name,
            null,
            $group->toArray(),
            "Created new attribute group: {$group->name}"
        );

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Attribute group created successfully.', 'group' => $group]);
        }

        return redirect()->route('admin.attribute-groups.index')->with('success', 'Attribute group created successfully.');
    }

    /**
     * Display attribute group details
     */
    public function show(AttributeGroup $attribute_group)
    {
        $attribute_group->load('attributes', 'categories');
        return view('admin.pages.attribute-groups.show', compact('attribute_group'));
    }

    /**
     * Show form for editing attribute group
     */
    public function edit($id)
    {   
        $group = AttributeGroup::find($id);
    if (!$group) {
        return redirect()->route('admin.pages.attribute-groups.index')
            ->with('error', 'Attribute group not found.');
    }
    
    return view('admin.pages.attribute-groups.edit', compact('group'));
    }

    /**
     * Update attribute group
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the group
            $group = AttributeGroup::find($id);
            
            if (!$group) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Attribute group not found.'
                    ], 404);
                }
                return redirect()->route('admin.attribute-groups.index')
                    ->with('error', 'Attribute group not found.');
            }
            
            $oldData = $group->toArray();
            
            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255|unique:attribute_groups,name,' . $id,
                'description' => 'nullable|string',
                'order' => 'nullable|integer',
                'is_collapsible' => 'boolean',
                'is_open_by_default' => 'boolean',
                'icon' => 'nullable|string|max:100',
                'position' => 'nullable|in:top,sidebar,bottom',
                'status' => 'boolean',
            ]);
            
            // Prepare data for update
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'order' => $request->order ?? 0,
                'is_collapsible' => $request->has('is_collapsible'),
                'is_open_by_default' => $request->has('is_open_by_default'),
                'icon' => $request->icon,
                'position' => $request->position ?? 'top',
                'status' => $request->has('status'),
            ];
            
            // Update the group
            $group->update($data);
            
            // Log activity
            $this->logActivity(
                'update',
                'attribute_group',
                'admin',
                $group->id,
                $group->name,
                $oldData,
                $group->toArray(),
                "Updated attribute group: {$group->name}"
            );
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attribute group updated successfully.',
                    'group' => $group
                ]);
            }
            
            return redirect()->route('admin.pages.attribute-groups.index')
                ->with('success', 'Attribute group updated successfully.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update attribute group: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update attribute group: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete attribute group
     */
    public function destroy(AttributeGroup $group)
    {
        if ($group->attributes()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete group because it has ' . $group->attributes()->count() . ' attributes.'
            ], 422);
        }

        $groupName = $group->name;
        $group->delete();

        $this->logActivity('delete', 'attribute_group', 'admin', $group->id, $groupName, null, null, "Deleted attribute group: {$groupName}");

        return response()->json(['success' => true, 'message' => 'Attribute group deleted successfully.']);
    }

    /**
     * Toggle group status
     */
    public function toggleStatus(AttributeGroup $group)
    {
        $oldStatus = $group->status;
        $group->update(['status' => !$group->status]);

        $this->logActivity('toggle_status', 'attribute_group', 'admin', $group->id, $group->name, ['status' => $oldStatus], ['status' => $group->status], "Toggled group status for '{$group->name}'");

        return response()->json(['success' => true, 'message' => 'Status updated.', 'status' => $group->status]);
    }

    /**
     * Bulk action on groups
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,approve,reject',
            'group_ids' => 'required|string',
        ]);

        $action = $request->action;
        $groupIds = json_decode($request->group_ids);
        $groups = AttributeGroup::whereIn('id', $groupIds)->get();
        $count = 0;
        $errors = [];

        foreach ($groups as $group) {
            try {
                switch ($action) {
                    case 'activate':
                        $group->update(['status' => true]);
                        $count++;
                        break;
                    case 'deactivate':
                        $group->update(['status' => false]);
                        $count++;
                        break;
                    case 'delete':
                        if ($group->attributes()->count() > 0) {
                            $errors[] = "Cannot delete '{$group->name}' - has attributes.";
                            continue 2;
                        }
                        $group->delete();
                        $count++;
                        break;
                    case 'approve':
                        if ($group->approval_status === 'pending') {
                            $group->update(['approval_status' => 'approved', 'approved_by' => auth('admin')->id(), 'approved_at' => now(), 'status' => true]);
                            $count++;
                        }
                        break;
                    case 'reject':
                        if ($group->approval_status === 'pending') {
                            $group->update(['approval_status' => 'rejected', 'approved_by' => auth('admin')->id(), 'approved_at' => now(), 'status' => false]);
                            $count++;
                        }
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$group->name}': " . $e->getMessage();
            }
        }

        $message = "{$count} groups processed successfully." . (!empty($errors) ? " Errors: " . implode(' ', $errors) : "");
        return response()->json(['success' => true, 'message' => $message, 'count' => $count, 'errors' => $errors]);
    }

    /**
     * Analytics dashboard
     */
    public function analytics()
{
    // Basic statistics
    $totalGroups = AttributeGroup::count();
    $activeGroups = AttributeGroup::where('status', true)->count();
    $groupsWithAttributes = AttributeGroup::has('attributes')->count();
    $totalAttributes = Attribute::count();
    $filterableAttributes = Attribute::where('is_filterable', true)->count();
    
    // Average attributes per group
    $avgAttributesPerGroup = $totalGroups > 0 ? Attribute::count() / $totalGroups : 0;
    
    // Groups with most attributes
    $groupsWithMostAttributes = AttributeGroup::withCount('attributes')
        ->having('attributes_count', '>', 0)
        ->orderBy('attributes_count', 'desc')
        ->take(10)
        ->get();
    
    // Groups by position
    $groupsByPosition = AttributeGroup::select('position', DB::raw('count(*) as count'))
        ->groupBy('position')
        ->get();
    
    // Groups by status
    $groupsByStatus = [
        (object)['status' => 'active', 'count' => $activeGroups],
        (object)['status' => 'inactive', 'count' => $totalGroups - $activeGroups]
    ];
    
    // Pending approval count
    $pendingCount = AttributeGroup::where('approval_status', 'pending')->count();
    $pendingIds = AttributeGroup::where('approval_status', 'pending')->pluck('id')->toArray();
    
    return view('admin.pages.attribute-groups.analytics', compact(
        'totalGroups',
        'activeGroups',
        'groupsWithAttributes',
        'avgAttributesPerGroup',
        'totalAttributes',
        'filterableAttributes',
        'groupsWithMostAttributes',
        'groupsByPosition',
        'groupsByStatus',
        'pendingCount',
        'pendingIds'
    ));
}
}
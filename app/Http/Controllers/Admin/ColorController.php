<?php
// app/Http/Controllers/Admin/ColorController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ColorRequest;
use App\Models\ColorAnalytic;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class ColorController extends Controller implements HasMiddleware
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
            new Middleware('permission:view_colors', only: ['index', 'show', 'analytics', 'pendingRequests', 'viewRequest']),
            new Middleware('permission:create_colors', only: ['create', 'store']),
            new Middleware('permission:edit_colors', only: ['edit', 'update', 'toggleStatus', 'toggleFeatured', 'togglePopular', 'bulkAction']),
            new Middleware('permission:approve_colors', only: ['approveRequest', 'rejectRequest']),
            new Middleware('permission:delete_colors', only: ['destroy', 'deleteRequest']),
        ];
    }

    /**
     * Display a listing of colors.
     */
    public function index(Request $request)
    {
        $query = Color::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', true);
            } elseif ($request->status === 'inactive') {
                $query->where('status', false);
            }
        }

        // Filter by approval status
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // Filter by featured/popular
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'true');
        }
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'true');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'order');
        $sortOrder = $request->get('sort_order', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'code':
                $query->orderBy('code', $sortOrder);
                break;
            case 'usage_count':
                $query->orderBy('usage_count', 'desc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('order', 'asc');
        }

        $colors = $query->paginate(15);

        // Get analytics for each color
        foreach ($colors as $color) {
            $analytics = ColorAnalytic::where('color_id', $color->id)
                ->where('date', today()->toDateString())
                ->first();
            $color->view_count = $analytics->view_count ?? 0;
            $color->product_count = $analytics->product_count ?? 0;
            $color->order_count = $analytics->order_count ?? 0;
            $color->total_revenue = $analytics->total_revenue ?? 0;
        }

        // Statistics
        $statistics = [
            'total' => Color::count(),
            'active' => Color::where('status', true)->count(),
            'featured' => Color::where('is_featured', true)->count(),
            'total_usage' => Color::sum('usage_count'),
        ];

        if ($request->ajax()) {
            $table = view('admin.pages.colors.partials.colors-table', compact('colors'))->render();
            $pagination = $colors->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.colors.index', compact('colors', 'statistics'));
    }

    /**
     * Display pending color requests from vendors
     */
    public function pendingRequests(Request $request)
    {
        $query = ColorRequest::with('vendor');

        // Filter by status
        $status = $request->get('status', 'pending');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('requested_name', 'like', "%{$search}%")
                    ->orWhere('requested_code', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total_pending' => ColorRequest::where('status', 'pending')->count(),
            'total_approved' => ColorRequest::where('status', 'approved')->count(),
            'total_rejected' => ColorRequest::where('status', 'rejected')->count(),
            'total_requests' => ColorRequest::count(),
        ];

        return view('admin.pages.colors.requests', compact('requests', 'statistics', 'status'));
    }

    /**
     * View single color request details
     */
    public function viewRequest($id)
    {
        $colorRequest = ColorRequest::with('vendor', 'approvedBy', 'createdColor')->findOrFail($id);
        return view('admin.pages.colors.request-details', compact('colorRequest'));
    }

    /**
     * Approve a color request and create the color
     */
    public function approveRequest(Request $request, $id)
    {
        $colorRequest = ColorRequest::with('vendor')->findOrFail($id);

        if ($colorRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        // Check if color already exists
        $existingColor = Color::where('code', $colorRequest->requested_code)->first();
        if ($existingColor) {
            return response()->json([
                'success' => false,
                'message' => 'A color with hex code ' . $colorRequest->requested_code . ' already exists. Please reject this request.'
            ], 422);
        }

        // Create the color in main colors table
        $color = Color::create([
            'name' => $colorRequest->requested_name,
            'slug' => Str::slug($colorRequest->requested_name),
            'code' => $colorRequest->requested_code,
            'rgb' => $colorRequest->requested_rgb,
            'hsl' => $colorRequest->requested_hsl,
            'description' => $colorRequest->description,
            'image' => $colorRequest->image,
            'image_alt' => $colorRequest->requested_name . ' color swatch',
            'requested_by' => $colorRequest->vendor_id,
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
            'order' => Color::max('order') + 1,
        ]);

        // Update the request
        $colorRequest->update([
            'status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'created_color_id' => $color->id,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'approve_color_request',
            'color_request',
            'admin',
            $colorRequest->id,
            $colorRequest->requested_name,
            null,
            [
                'request_id' => $colorRequest->id,
                'color_id' => $color->id,
                'vendor_id' => $colorRequest->vendor_id,
                'vendor_name' => $colorRequest->vendor->name ?? 'Unknown',
            ],
            "Approved color request '{$colorRequest->requested_name}' from Vendor #{$colorRequest->vendor_id} and created color"
        );

        return response()->json([
            'success' => true,
            'message' => 'Color request approved and color created successfully.',
            'color' => $color
        ]);
    }

    /**
     * Reject a color request
     */
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $colorRequest = ColorRequest::findOrFail($id);

        if ($colorRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $colorRequest->update([
            'status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'reject_color_request',
            'color_request',
            'admin',
            $colorRequest->id,
            $colorRequest->requested_name,
            null,
            [
                'request_id' => $colorRequest->id,
                'vendor_id' => $colorRequest->vendor_id,
                'rejection_reason' => $request->rejection_reason,
            ],
            "Rejected color request '{$colorRequest->requested_name}' - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Color request rejected successfully.'
        ]);
    }

    /**
     * Delete a color request (for rejected/spam requests)
     */
    public function deleteRequest($id)
    {
        $colorRequest = ColorRequest::findOrFail($id);

        if ($colorRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved requests. Delete the color instead.'
            ], 422);
        }

        $requestName = $colorRequest->requested_name;
        $colorRequest->delete();

        $this->logActivity(
            'delete_color_request',
            'color_request',
            'admin',
            $id,
            $requestName,
            null,
            null,
            "Deleted color request '{$requestName}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Color request deleted successfully.'
        ]);
    }

    /**
     * Bulk action on color requests
     */
    public function bulkRequestAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'request_ids' => 'required|string',
        ]);

        $action = $request->action;
        $requestIds = json_decode($request->request_ids);
        $requests = ColorRequest::whereIn('id', $requestIds)->get();

        $count = 0;
        $errors = [];
        $processedRequests = [];

        foreach ($requests as $colorRequest) {
            try {
                if ($colorRequest->status !== 'pending' && in_array($action, ['approve', 'reject'])) {
                    $errors[] = "Request '{$colorRequest->requested_name}' is already processed.";
                    continue;
                }

                switch ($action) {
                    case 'approve':
                        // Check if color already exists
                        $existingColor = Color::where('code', $colorRequest->requested_code)->first();
                        if ($existingColor) {
                            $errors[] = "Color '{$colorRequest->requested_name}' already exists with hex code {$colorRequest->requested_code}.";
                            continue 2;
                        }

                        $color = Color::create([
                            'name' => $colorRequest->requested_name,
                            'slug' => Str::slug($colorRequest->requested_name),
                            'code' => $colorRequest->requested_code,
                            'rgb' => $colorRequest->requested_rgb,
                            'hsl' => $colorRequest->requested_hsl,
                            'description' => $colorRequest->description,
                            'image' => $colorRequest->image,
                            'requested_by' => $colorRequest->vendor_id,
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'approval_status' => 'approved',
                            'status' => true,
                            'order' => Color::max('order') + 1,
                        ]);

                        $colorRequest->update([
                            'status' => 'approved',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'created_color_id' => $color->id,
                        ]);
                        $count++;
                        $processedRequests[] = $colorRequest->requested_name;
                        break;

                    case 'reject':
                        $colorRequest->update([
                            'status' => 'rejected',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'rejection_reason' => $request->rejection_reason ?? 'Bulk rejection',
                        ]);
                        $count++;
                        $processedRequests[] = $colorRequest->requested_name;
                        break;

                    case 'delete':
                        if ($colorRequest->status === 'approved') {
                            $errors[] = "Cannot delete approved request '{$colorRequest->requested_name}'.";
                            continue 2;
                        }
                        $colorRequest->delete();
                        $count++;
                        $processedRequests[] = $colorRequest->requested_name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$colorRequest->requested_name}': " . $e->getMessage();
            }
        }

        // Log bulk action
        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action . '_requests',
                'color_request',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_requests' => $processedRequests,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} color requests"
            );
        }

        $message = "{$count} requests processed successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(' ', $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count,
            'errors' => $errors
        ]);
    }

    /**
     * Color Analytics Dashboard
     */
    public function analytics()
    {
        // Top colors by views
        $topViewsColors = Color::select('colors.id', 'colors.name', 'colors.code')
            ->join('color_analytics', 'colors.id', '=', 'color_analytics.color_id')
            ->selectRaw('SUM(color_analytics.view_count) as total_views')
            ->where('colors.status', true)
            ->groupBy('colors.id', 'colors.name', 'colors.code')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();

        // Top colors by revenue
        $topRevenueColors = Color::select('colors.id', 'colors.name', 'colors.code')
            ->join('color_analytics', 'colors.id', '=', 'color_analytics.color_id')
            ->selectRaw('SUM(color_analytics.total_revenue) as total_revenue')
            ->where('colors.status', true)
            ->where('color_analytics.total_revenue', '>', 0)
            ->groupBy('colors.id', 'colors.name', 'colors.code')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Most used colors
        $mostUsedColors = Color::where('status', true)
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get();

        // Pending approval colors
        $pendingColors = Color::where('approval_status', 'pending')
            ->with('requestedBy')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $totalColors = Color::count();
        $activeColors = Color::where('status', true)->count();
        $featuredColors = Color::where('is_featured', true)->count();
        $popularColors = Color::where('is_popular', true)->count();
        $pendingCount = Color::where('approval_status', 'pending')->count();
        $approvedCount = Color::where('approval_status', 'approved')->count();
        $rejectedCount = Color::where('approval_status', 'rejected')->count();

        $totalViews = ColorAnalytic::sum('view_count');
        $totalRevenue = ColorAnalytic::sum('total_revenue');

        return view('admin.pages.colors.analytics', compact(
            'topViewsColors',
            'topRevenueColors',
            'mostUsedColors',
            'pendingColors',
            'totalColors',
            'activeColors',
            'featuredColors',
            'popularColors',
            'totalViews',
            'totalRevenue',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show form for creating new color.
     */
    public function create()
    {
        return view('admin.pages.colors.create');
    }

    /**
     * Store newly created color.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name',
            'code' => 'required|string|max:7|unique:colors,code',
            'rgb' => 'nullable|string|max:50',
            'hsl' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['image']);

        // Handle image
        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'colors', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
                $data['image_alt'] = $request->image_alt ?? $request->name;
            }
        }

        $data['slug'] = Str::slug($request->name);
        $data['approval_status'] = 'approved';

        $color = Color::create($data);

        // Log activity
        $this->logActivity(
            'create',
            'color',
            'admin',
            $color->id,
            $color->name,
            null,
            $color->toArray(),
            "Created new color: {$color->name} ({$color->code})"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Color created successfully.',
                'color' => $color
            ]);
        }

        return redirect()->route('admin.colors.index')->with('success', 'Color created successfully.');
    }

    /**
     * Display color details.
     */
    public function show(Color $color)
    {
        $color->load('requestedBy', 'approvedBy');

        $recentAnalytics = ColorAnalytic::where('color_id', $color->id)
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('admin.pages.colors.show', compact('color', 'recentAnalytics'));
    }

    /**
     * Show form for editing color.
     */
    public function edit(Color $color)
    {
        return view('admin.pages.colors.edit', compact('color'));
    }

    /**
     * Update color.
     */
    public function update(Request $request, Color $color)
    {
        $oldData = $color->toArray();

        $request->validate([
            'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
            'code' => 'required|string|max:7|unique:colors,code,' . $color->id,
            'rgb' => 'nullable|string|max:50',
            'hsl' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'focus_keyword' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['image', 'remove_image']);

        // Handle image
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('colors/' . $color->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'colors', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
                $data['image_alt'] = $request->image_alt ?? $request->name;
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('colors/' . $color->image);
            $data['image'] = null;
            $data['image_alt'] = null;
        }

        $color->update($data);

        // Log activity
        $changes = $this->getChanges($oldData, $color->toArray());
        $this->logActivity(
            'update',
            'color',
            'admin',
            $color->id,
            $color->name,
            $oldData,
            $color->toArray(),
            "Updated color: {$color->name}" . (!empty($changes) ? " - Changes: " . implode(', ', $changes) : "")
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Color updated successfully.'
            ]);
        }

        return redirect()->route('admin.colors.index')->with('success', 'Color updated successfully.');
    }

    /**
     * Approve pending color (for vendor requests)
     */
    public function approveColor(Color $color)
    {
        if ($color->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This color is not pending approval.'
            ], 422);
        }

        $oldStatus = $color->approval_status;
        $color->update([
            'approval_status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'status' => true
        ]);

        $this->logActivity(
            'approve',
            'color',
            'admin',
            $color->id,
            $color->name,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'approved'],
            "Approved color: {$color->name} ({$color->code})"
        );

        return response()->json([
            'success' => true,
            'message' => 'Color approved successfully.'
        ]);
    }

    /**
     * Reject pending color (for vendor requests)
     */
    public function rejectColor(Request $request, Color $color)
    {
        if ($color->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This color is not pending approval.'
            ], 422);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $oldStatus = $color->approval_status;
        $color->update([
            'approval_status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'status' => false
        ]);

        $this->logActivity(
            'reject',
            'color',
            'admin',
            $color->id,
            $color->name,
            ['approval_status' => $oldStatus],
            ['approval_status' => 'rejected', 'rejection_reason' => $request->rejection_reason],
            "Rejected color: {$color->name} ({$color->code}) - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Color rejected successfully.'
        ]);
    }

    /**
     * Delete color.
     */
    public function destroy(Color $color)
    {
        if ($color->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete color because it has ' . $color->products()->count() . ' products assigned.'
            ], 422);
        }

        $colorData = $color->toArray();
        $colorName = $color->name;

        $this->deleteImageIfExists('colors/' . $color->image);
        ColorAnalytic::where('color_id', $color->id)->delete();
        $color->delete();

        $this->logActivity(
            'delete',
            'color',
            'admin',
            $color->id,
            $colorName,
            $colorData,
            null,
            "Deleted color: {$colorName}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Color deleted successfully.'
        ]);
    }

    /**
     * Toggle color status.
     */
    public function toggleStatus(Color $color)
    {
        $oldStatus = $color->status;
        $color->update(['status' => !$color->status]);

        $this->logActivity(
            'toggle_status',
            'color',
            'admin',
            $color->id,
            $color->name,
            ['status' => $oldStatus],
            ['status' => $color->status],
            "Toggled color status for '{$color->name}' from " . ($oldStatus ? 'Active' : 'Inactive') . " to " . ($color->status ? 'Active' : 'Inactive')
        );

        return response()->json([
            'success' => true,
            'message' => 'Color status updated.',
            'status' => $color->status
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Color $color)
    {
        $oldValue = $color->is_featured;
        $color->update(['is_featured' => !$color->is_featured]);

        $this->logActivity(
            'toggle_featured',
            'color',
            'admin',
            $color->id,
            $color->name,
            ['is_featured' => $oldValue],
            ['is_featured' => $color->is_featured],
            ($color->is_featured ? 'Marked' : 'Unmarked') . " color '{$color->name}' as featured"
        );

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $color->is_featured
        ]);
    }

    /**
     * Toggle popular status.
     */
    public function togglePopular(Color $color)
    {
        $oldValue = $color->is_popular;
        $color->update(['is_popular' => !$color->is_popular]);

        $this->logActivity(
            'toggle_popular',
            'color',
            'admin',
            $color->id,
            $color->name,
            ['is_popular' => $oldValue],
            ['is_popular' => $color->is_popular],
            ($color->is_popular ? 'Marked' : 'Unmarked') . " color '{$color->name}' as popular"
        );

        return response()->json([
            'success' => true,
            'message' => 'Popular status updated.',
            'is_popular' => $color->is_popular
        ]);
    }

    /**
     * Bulk action on colors
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,popular,unpopular,approve,reject',
            'color_ids' => 'required|string',
        ]);

        $action = $request->action;
        $colorIds = json_decode($request->color_ids);

        $colors = Color::whereIn('id', $colorIds)->get();
        $count = 0;
        $errors = [];
        $processedColors = [];

        foreach ($colors as $color) {
            try {
                $oldData = $color->toArray();

                switch ($action) {
                    case 'activate':
                        $color->update(['status' => true]);
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                    case 'deactivate':
                        $color->update(['status' => false]);
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                    case 'feature':
                        $color->update(['is_featured' => true]);
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                    case 'unfeature':
                        $color->update(['is_featured' => false]);
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                    case 'popular':
                        $color->update(['is_popular' => true]);
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                    case 'unpopular':
                        $color->update(['is_popular' => false]);
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                    case 'approve':
                        if ($color->approval_status === 'pending') {
                            $color->update([
                                'approval_status' => 'approved',
                                'approved_by' => auth('admin')->id(),
                                'approved_at' => now(),
                                'status' => true
                            ]);
                            $count++;
                            $processedColors[] = $color->name;
                        }
                        break;
                    case 'reject':
                        if ($color->approval_status === 'pending') {
                            $color->update([
                                'approval_status' => 'rejected',
                                'approved_by' => auth('admin')->id(),
                                'approved_at' => now(),
                                'status' => false
                            ]);
                            $count++;
                            $processedColors[] = $color->name;
                        }
                        break;
                    case 'delete':
                        if ($color->products()->count() > 0) {
                            $errors[] = "Cannot delete '{$color->name}' because it has products.";
                            continue 2;
                        }
                        $this->deleteImageIfExists('colors/' . $color->image);
                        ColorAnalytic::where('color_id', $color->id)->delete();
                        $color->delete();
                        $count++;
                        $processedColors[] = $color->name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$color->name}': " . $e->getMessage();
            }
        }

        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action,
                'color',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_colors' => $processedColors,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} colors: " . implode(', ', $processedColors)
            );
        }

        $message = "{$count} colors processed successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(' ', $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count,
            'errors' => $errors
        ]);
    }

    /**
     * Delete image if exists
     */
    private function deleteImageIfExists($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Get changes between old and new data
     */
    private function getChanges($oldData, $newData)
    {
        $changes = [];
        $fields = ['name', 'code', 'status', 'is_featured', 'is_popular', 'order'];

        foreach ($fields as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] != $newData[$field]) {
                $changes[] = $field . " changed from '{$oldData[$field]}' to '{$newData[$field]}'";
            }
        }

        return $changes;
    }
}

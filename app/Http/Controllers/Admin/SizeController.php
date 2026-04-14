<?php
// app/Http/Controllers/Admin/SizeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\SizeRequest;
use App\Models\SizeAnalytic;
use App\Models\Category;
use App\Services\ImageCompressionService;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;

class SizeController extends Controller implements HasMiddleware
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
            new Middleware('permission:view_sizes', only: ['index', 'show', 'analytics', 'pendingRequests', 'viewRequest']),
            new Middleware('permission:create_sizes', only: ['create', 'store']),
            new Middleware('permission:edit_sizes', only: ['edit', 'update', 'toggleStatus', 'toggleFeatured', 'togglePopular', 'bulkAction']),
            new Middleware('permission:approve_sizes', only: ['approveRequest', 'rejectRequest']),
            new Middleware('permission:delete_sizes', only: ['destroy', 'deleteRequest']),
        ];
    }

    /**
     * Display a listing of sizes.
     */
    public function index(Request $request)
    {
        $query = Size::with('categories');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('us_size', 'like', "%{$search}%")
                    ->orWhere('eu_size', 'like', "%{$search}%");
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

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
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
            case 'gender':
                $query->orderBy('gender', $sortOrder);
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

        $sizes = $query->paginate(15);

        // Get analytics for each size
        foreach ($sizes as $size) {
            $analytics = SizeAnalytic::where('size_id', $size->id)
                ->where('date', today()->toDateString())
                ->first();
            $size->view_count = $analytics->view_count ?? 0;
            $size->product_count = $analytics->product_count ?? 0;
            $size->order_count = $analytics->order_count ?? 0;
            $size->total_revenue = $analytics->total_revenue ?? 0;
        }

        // Statistics
        $statistics = [
            'total' => Size::count(),
            'active' => Size::where('status', true)->count(),
            'featured' => Size::where('is_featured', true)->count(),
            'popular' => Size::where('is_popular', true)->count(),
            'pending' => Size::where('approval_status', 'pending')->count(),
            'rejected' => Size::where('approval_status', 'rejected')->count(),
            'total_usage' => Size::sum('usage_count'),
            'men' => Size::where('gender', 'Men')->count(),
            'women' => Size::where('gender', 'Women')->count(),
            'unisex' => Size::where('gender', 'Unisex')->count(),
            'kids' => Size::where('gender', 'Kids')->count(),
        ];

        // Get categories for filter dropdown
        $categories = Category::where('status', true)->orderBy('name')->get();

        if ($request->ajax()) {
            $table = view('admin.pages.sizes.partials.sizes-table', compact('sizes'))->render();
            $pagination = $sizes->appends($request->query())->links('pagination::bootstrap-5')->render();

            return response()->json([
                'table' => $table,
                'pagination' => $pagination,
                'statistics' => $statistics
            ]);
        }

        return view('admin.pages.sizes.index', compact('sizes', 'statistics', 'categories'));
    }

    /**
     * Display pending size requests from vendors
     */
    public function pendingRequests(Request $request)
    {
        $query = SizeRequest::with('vendor');

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

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('requested_gender', $request->gender);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total_pending' => SizeRequest::where('status', 'pending')->count(),
            'total_approved' => SizeRequest::where('status', 'approved')->count(),
            'total_rejected' => SizeRequest::where('status', 'rejected')->count(),
            'total_requests' => SizeRequest::count(),
        ];

        return view('admin.pages.sizes.requests', compact('requests', 'statistics', 'status'));
    }

    /**
     * View single size request details
     */
    public function viewRequest($id)
    {
        $sizeRequest = SizeRequest::with('vendor', 'approvedBy', 'createdSize')->findOrFail($id);
        return view('admin.pages.sizes.request-details', compact('sizeRequest'));
    }

    /**
     * Approve a size request and create the size
     */
    public function approveRequest(Request $request, $id)
    {
        $sizeRequest = SizeRequest::with('vendor')->findOrFail($id);

        if ($sizeRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        // Check if size already exists
        $existingSize = Size::where('code', $sizeRequest->requested_code)->first();
        if ($existingSize) {
            return response()->json([
                'success' => false,
                'message' => 'A size with code "' . $sizeRequest->requested_code . '" already exists. Please reject this request.'
            ], 422);
        }

        // Create the size
        $size = Size::create([
            'name' => $sizeRequest->requested_name,
            'slug' => Str::slug($sizeRequest->requested_name . '-' . $sizeRequest->requested_gender),
            'code' => $sizeRequest->requested_code,
            'gender' => $sizeRequest->requested_gender,
            'chest' => $sizeRequest->requested_chest,
            'waist' => $sizeRequest->requested_waist,
            'hip' => $sizeRequest->requested_hip,
            'inseam' => $sizeRequest->requested_inseam,
            'shoulder' => $sizeRequest->requested_shoulder,
            'sleeve' => $sizeRequest->requested_sleeve,
            'neck' => $sizeRequest->requested_neck,
            'height' => $sizeRequest->requested_height,
            'weight' => $sizeRequest->requested_weight,
            'us_size' => $sizeRequest->requested_us_size,
            'uk_size' => $sizeRequest->requested_uk_size,
            'eu_size' => $sizeRequest->requested_eu_size,
            'au_size' => $sizeRequest->requested_au_size,
            'jp_size' => $sizeRequest->requested_jp_size,
            'cn_size' => $sizeRequest->requested_cn_size,
            'int_size' => $sizeRequest->requested_int_size,
            'description' => $sizeRequest->description,
            'image' => $sizeRequest->image,
            'requested_by' => $sizeRequest->vendor_id,
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'approval_status' => 'approved',
            'status' => true,
            'order' => Size::max('order') + 1,
        ]);

        // Attach to categories
        if ($sizeRequest->requested_category_ids) {
            $size->categories()->attach($sizeRequest->requested_category_ids);
        }

        // Update the request
        $sizeRequest->update([
            'status' => 'approved',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'created_size_id' => $size->id,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'approve_size_request',
            'size_request',
            'admin',
            $sizeRequest->id,
            $sizeRequest->requested_name,
            null,
            [
                'request_id' => $sizeRequest->id,
                'size_id' => $size->id,
                'vendor_id' => $sizeRequest->vendor_id,
                'vendor_name' => $sizeRequest->vendor->name ?? 'Unknown',
            ],
            "Approved size request '{$sizeRequest->requested_name}' from Vendor #{$sizeRequest->vendor_id} and created size"
        );

        return response()->json([
            'success' => true,
            'message' => 'Size request approved and size created successfully.',
            'size' => $size
        ]);
    }

    /**
     * Reject a size request
     */
    public function rejectRequest(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $sizeRequest = SizeRequest::findOrFail($id);

        if ($sizeRequest->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already been processed.'
            ], 422);
        }

        $sizeRequest->update([
            'status' => 'rejected',
            'approved_by' => auth('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
        ]);

        // Log activity
        $this->logActivity(
            'reject_size_request',
            'size_request',
            'admin',
            $sizeRequest->id,
            $sizeRequest->requested_name,
            null,
            [
                'request_id' => $sizeRequest->id,
                'vendor_id' => $sizeRequest->vendor_id,
                'rejection_reason' => $request->rejection_reason,
            ],
            "Rejected size request '{$sizeRequest->requested_name}' - Reason: {$request->rejection_reason}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Size request rejected successfully.'
        ]);
    }

    /**
     * Delete a size request
     */
    public function deleteRequest($id)
    {
        $sizeRequest = SizeRequest::findOrFail($id);

        if ($sizeRequest->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete approved requests. Delete the size instead.'
            ], 422);
        }

        $requestName = $sizeRequest->requested_name;
        $sizeRequest->delete();

        $this->logActivity(
            'delete_size_request',
            'size_request',
            'admin',
            $id,
            $requestName,
            null,
            null,
            "Deleted size request '{$requestName}'"
        );

        return response()->json([
            'success' => true,
            'message' => 'Size request deleted successfully.'
        ]);
    }

    /**
     * Bulk action on size requests
     */
    public function bulkRequestAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'request_ids' => 'required|string',
        ]);

        $action = $request->action;
        $requestIds = json_decode($request->request_ids);
        $requests = SizeRequest::whereIn('id', $requestIds)->get();

        $count = 0;
        $errors = [];
        $processedRequests = [];

        foreach ($requests as $sizeRequest) {
            try {
                if ($sizeRequest->status !== 'pending' && in_array($action, ['approve', 'reject'])) {
                    $errors[] = "Request '{$sizeRequest->requested_name}' is already processed.";
                    continue;
                }

                switch ($action) {
                    case 'approve':
                        $existingSize = Size::where('code', $sizeRequest->requested_code)->first();
                        if ($existingSize) {
                            $errors[] = "Size '{$sizeRequest->requested_name}' already exists with code {$sizeRequest->requested_code}.";
                            continue 2;
                        }

                        $size = Size::create([
                            'name' => $sizeRequest->requested_name,
                            'slug' => Str::slug($sizeRequest->requested_name . '-' . $sizeRequest->requested_gender),
                            'code' => $sizeRequest->requested_code,
                            'gender' => $sizeRequest->requested_gender,
                            'chest' => $sizeRequest->requested_chest,
                            'waist' => $sizeRequest->requested_waist,
                            'hip' => $sizeRequest->requested_hip,
                            'inseam' => $sizeRequest->requested_inseam,
                            'shoulder' => $sizeRequest->requested_shoulder,
                            'sleeve' => $sizeRequest->requested_sleeve,
                            'neck' => $sizeRequest->requested_neck,
                            'height' => $sizeRequest->requested_height,
                            'weight' => $sizeRequest->requested_weight,
                            'us_size' => $sizeRequest->requested_us_size,
                            'uk_size' => $sizeRequest->requested_uk_size,
                            'eu_size' => $sizeRequest->requested_eu_size,
                            'au_size' => $sizeRequest->requested_au_size,
                            'jp_size' => $sizeRequest->requested_jp_size,
                            'cn_size' => $sizeRequest->requested_cn_size,
                            'int_size' => $sizeRequest->requested_int_size,
                            'description' => $sizeRequest->description,
                            'image' => $sizeRequest->image,
                            'requested_by' => $sizeRequest->vendor_id,
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'approval_status' => 'approved',
                            'status' => true,
                            'order' => Size::max('order') + 1,
                        ]);

                        if ($sizeRequest->requested_category_ids) {
                            $size->categories()->attach($sizeRequest->requested_category_ids);
                        }

                        $sizeRequest->update([
                            'status' => 'approved',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'created_size_id' => $size->id,
                        ]);
                        $count++;
                        $processedRequests[] = $sizeRequest->requested_name;
                        break;

                    case 'reject':
                        $sizeRequest->update([
                            'status' => 'rejected',
                            'approved_by' => auth('admin')->id(),
                            'approved_at' => now(),
                            'rejection_reason' => $request->rejection_reason ?? 'Bulk rejection',
                        ]);
                        $count++;
                        $processedRequests[] = $sizeRequest->requested_name;
                        break;

                    case 'delete':
                        if ($sizeRequest->status === 'approved') {
                            $errors[] = "Cannot delete approved request '{$sizeRequest->requested_name}'.";
                            continue 2;
                        }
                        $sizeRequest->delete();
                        $count++;
                        $processedRequests[] = $sizeRequest->requested_name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$sizeRequest->requested_name}': " . $e->getMessage();
            }
        }

        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action . '_requests',
                'size_request',
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
                "Bulk {$action} performed on {$count} size requests"
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
     * Show form for creating new size.
     */
    public function create()
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        return view('admin.pages.sizes.create', compact('categories'));
    }

    /**
     * Store newly created size.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
            'code' => 'required|string|max:20|unique:sizes,code',
            'gender' => 'required|in:Men,Women,Unisex,Kids',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hip' => 'nullable|numeric',
            'inseam' => 'nullable|numeric',
            'shoulder' => 'nullable|numeric',
            'sleeve' => 'nullable|numeric',
            'neck' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'us_size' => 'nullable|string|max:20',
            'uk_size' => 'nullable|string|max:20',
            'eu_size' => 'nullable|string|max:20',
            'int_size' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'category_ids']);

        // Handle image
        if ($request->hasFile('image')) {
            $compressed = $this->imageCompressor->compress($request->file('image'), 'sizes', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
                $data['image_alt'] = $request->image_alt ?? $request->name;
            }
        }

        $data['slug'] = Str::slug($request->name . '-' . $request->gender);
        $data['approval_status'] = 'approved';

        $size = Size::create($data);

        // Attach categories
        if ($request->has('category_ids')) {
            $size->categories()->attach($request->category_ids);
        }

        // Log activity
        $this->logActivity(
            'create',
            'size',
            'admin',
            $size->id,
            $size->name,
            null,
            $size->toArray(),
            "Created new size: {$size->name} ({$size->code}) for {$size->gender}"
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Size created successfully.',
                'size' => $size
            ]);
        }

        return redirect()->route('admin.sizes.index')->with('success', 'Size created successfully.');
    }

    /**
     * Display size details.
     */
    public function show(Size $size)
    {
        $size->load('categories', 'requestedBy', 'approvedBy');

        $recentAnalytics = SizeAnalytic::where('size_id', $size->id)
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('admin.pages.sizes.show', compact('size', 'recentAnalytics'));
    }

    /**
     * Show form for editing size.
     */
    public function edit(Size $size)
    {
        $categories = Category::where('status', true)->orderBy('name')->get();
        $selectedCategories = $size->categories->pluck('id')->toArray();
        return view('admin.pages.sizes.edit', compact('size', 'categories', 'selectedCategories'));
    }

    /**
     * Update size.
     */
    public function update(Request $request, Size $size)
    {
        $oldData = $size->toArray();

        $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
            'code' => 'required|string|max:20|unique:sizes,code,' . $size->id,
            'gender' => 'required|in:Men,Women,Unisex,Kids',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hip' => 'nullable|numeric',
            'inseam' => 'nullable|numeric',
            'shoulder' => 'nullable|numeric',
            'sleeve' => 'nullable|numeric',
            'neck' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'us_size' => 'nullable|string|max:20',
            'uk_size' => 'nullable|string|max:20',
            'eu_size' => 'nullable|string|max:20',
            'int_size' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $data = $request->except(['image', 'category_ids', 'remove_image']);

        // Handle image
        if ($request->hasFile('image')) {
            $this->deleteImageIfExists('sizes/' . $size->image);
            $compressed = $this->imageCompressor->compress($request->file('image'), 'sizes', 300, 85);
            if ($compressed['success']) {
                $data['image'] = $compressed['filename'];
                $data['image_alt'] = $request->image_alt ?? $request->name;
            }
        } elseif ($request->has('remove_image') && $request->remove_image) {
            $this->deleteImageIfExists('sizes/' . $size->image);
            $data['image'] = null;
            $data['image_alt'] = null;
        }

        $size->update($data);

        // Sync categories
        if ($request->has('category_ids')) {
            $size->categories()->sync($request->category_ids);
        } else {
            $size->categories()->detach();
        }

        // Log activity
        $changes = $this->getChanges($oldData, $size->toArray());
        $this->logActivity(
            'update',
            'size',
            'admin',
            $size->id,
            $size->name,
            $oldData,
            $size->toArray(),
            "Updated size: {$size->name}" . (!empty($changes) ? " - Changes: " . implode(', ', $changes) : "")
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Size updated successfully.'
            ]);
        }

        return redirect()->route('admin.sizes.index')->with('success', 'Size updated successfully.');
    }

    /**
     * Delete size.
     */
    public function destroy(Size $size)
    {
        if ($size->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete size because it has ' . $size->products()->count() . ' products assigned.'
            ], 422);
        }

        $sizeData = $size->toArray();
        $sizeName = $size->name;

        $this->deleteImageIfExists('sizes/' . $size->image);
        SizeAnalytic::where('size_id', $size->id)->delete();
        $size->categories()->detach();
        $size->delete();

        $this->logActivity(
            'delete',
            'size',
            'admin',
            $size->id,
            $sizeName,
            $sizeData,
            null,
            "Deleted size: {$sizeName}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Size deleted successfully.'
        ]);
    }

    /**
     * Toggle size status.
     */
    public function toggleStatus(Size $size)
    {
        $oldStatus = $size->status;
        $size->update(['status' => !$size->status]);

        $this->logActivity(
            'toggle_status',
            'size',
            'admin',
            $size->id,
            $size->name,
            ['status' => $oldStatus],
            ['status' => $size->status],
            "Toggled size status for '{$size->name}' from " . ($oldStatus ? 'Active' : 'Inactive') . " to " . ($size->status ? 'Active' : 'Inactive')
        );

        return response()->json([
            'success' => true,
            'message' => 'Size status updated.',
            'status' => $size->status
        ]);
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Size $size)
    {
        $oldValue = $size->is_featured;
        $size->update(['is_featured' => !$size->is_featured]);

        $this->logActivity(
            'toggle_featured',
            'size',
            'admin',
            $size->id,
            $size->name,
            ['is_featured' => $oldValue],
            ['is_featured' => $size->is_featured],
            ($size->is_featured ? 'Marked' : 'Unmarked') . " size '{$size->name}' as featured"
        );

        return response()->json([
            'success' => true,
            'message' => 'Featured status updated.',
            'is_featured' => $size->is_featured
        ]);
    }

    /**
     * Toggle popular status.
     */
    public function togglePopular(Size $size)
    {
        $oldValue = $size->is_popular;
        $size->update(['is_popular' => !$size->is_popular]);

        $this->logActivity(
            'toggle_popular',
            'size',
            'admin',
            $size->id,
            $size->name,
            ['is_popular' => $oldValue],
            ['is_popular' => $size->is_popular],
            ($size->is_popular ? 'Marked' : 'Unmarked') . " size '{$size->name}' as popular"
        );

        return response()->json([
            'success' => true,
            'message' => 'Popular status updated.',
            'is_popular' => $size->is_popular
        ]);
    }

    /**
     * Bulk action on sizes
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature,popular,unpopular,approve,reject',
            'size_ids' => 'required|string',
        ]);

        $action = $request->action;
        $sizeIds = json_decode($request->size_ids);
        $sizes = Size::whereIn('id', $sizeIds)->get();
        $count = 0;
        $errors = [];
        $processedSizes = [];

        foreach ($sizes as $size) {
            try {
                $oldData = $size->toArray();

                switch ($action) {
                    case 'activate':
                        $size->update(['status' => true]);
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                    case 'deactivate':
                        $size->update(['status' => false]);
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                    case 'feature':
                        $size->update(['is_featured' => true]);
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                    case 'unfeature':
                        $size->update(['is_featured' => false]);
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                    case 'popular':
                        $size->update(['is_popular' => true]);
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                    case 'unpopular':
                        $size->update(['is_popular' => false]);
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                    case 'approve':
                        if ($size->approval_status === 'pending') {
                            $size->update([
                                'approval_status' => 'approved',
                                'approved_by' => auth('admin')->id(),
                                'approved_at' => now(),
                                'status' => true
                            ]);
                            $count++;
                            $processedSizes[] = $size->name;
                        }
                        break;
                    case 'reject':
                        if ($size->approval_status === 'pending') {
                            $size->update([
                                'approval_status' => 'rejected',
                                'approved_by' => auth('admin')->id(),
                                'approved_at' => now(),
                                'status' => false
                            ]);
                            $count++;
                            $processedSizes[] = $size->name;
                        }
                        break;
                    case 'delete':
                        if ($size->products()->count() > 0) {
                            $errors[] = "Cannot delete '{$size->name}' because it has products.";
                            continue 2;
                        }
                        $this->deleteImageIfExists('sizes/' . $size->image);
                        SizeAnalytic::where('size_id', $size->id)->delete();
                        $size->categories()->detach();
                        $size->delete();
                        $count++;
                        $processedSizes[] = $size->name;
                        break;
                }
            } catch (\Exception $e) {
                $errors[] = "Error processing '{$size->name}': " . $e->getMessage();
            }
        }

        if ($count > 0) {
            $this->logActivity(
                'bulk_' . $action,
                'size',
                'admin',
                null,
                'Bulk Action',
                null,
                [
                    'action' => $action,
                    'affected_sizes' => $processedSizes,
                    'count' => $count,
                    'errors' => $errors
                ],
                "Bulk {$action} performed on {$count} sizes: " . implode(', ', $processedSizes)
            );
        }

        $message = "{$count} sizes processed successfully.";
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
     * Size Analytics Dashboard
     */
    public function analytics()
    {
        // Top sizes by views
        $topViewsSizes = Size::select('sizes.id', 'sizes.name', 'sizes.code', 'sizes.gender')
            ->join('size_analytics', 'sizes.id', '=', 'size_analytics.size_id')
            ->selectRaw('SUM(size_analytics.view_count) as total_views')
            ->where('sizes.status', true)
            ->groupBy('sizes.id', 'sizes.name', 'sizes.code', 'sizes.gender')
            ->orderBy('total_views', 'desc')
            ->take(10)
            ->get();

        // Top sizes by revenue
        $topRevenueSizes = Size::select('sizes.id', 'sizes.name', 'sizes.code', 'sizes.gender')
            ->join('size_analytics', 'sizes.id', '=', 'size_analytics.size_id')
            ->selectRaw('SUM(size_analytics.total_revenue) as total_revenue')
            ->where('sizes.status', true)
            ->where('size_analytics.total_revenue', '>', 0)
            ->groupBy('sizes.id', 'sizes.name', 'sizes.code', 'sizes.gender')
            ->orderBy('total_revenue', 'desc')
            ->take(10)
            ->get();

        // Most used sizes
        $mostUsedSizes = Size::where('status', true)
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get();

        // Statistics
        $totalSizes = Size::count();
        $activeSizes = Size::where('status', true)->count();
        $featuredSizes = Size::where('is_featured', true)->count();
        $popularSizes = Size::where('is_popular', true)->count();
        $pendingCount = Size::where('approval_status', 'pending')->count();
        $approvedCount = Size::where('approval_status', 'approved')->count();
        $rejectedCount = Size::where('approval_status', 'rejected')->count();
        $menSizes = Size::where('gender', 'Men')->count();
        $womenSizes = Size::where('gender', 'Women')->count();
        $unisexSizes = Size::where('gender', 'Unisex')->count();
        $kidsSizes = Size::where('gender', 'Kids')->count();

        $totalViews = SizeAnalytic::sum('view_count');
        $totalRevenue = SizeAnalytic::sum('total_revenue');

        return view('admin.pages.sizes.analytics', compact(
            'topViewsSizes',
            'topRevenueSizes',
            'mostUsedSizes',
            'totalSizes',
            'activeSizes',
            'featuredSizes',
            'popularSizes',
            'totalViews',
            'totalRevenue',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'menSizes',
            'womenSizes',
            'unisexSizes',
            'kidsSizes'
        ));
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
        $fields = ['name', 'code', 'gender', 'status', 'is_featured', 'is_popular', 'order'];

        foreach ($fields as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] != $newData[$field]) {
                $changes[] = $field . " changed from '{$oldData[$field]}' to '{$newData[$field]}'";
            }
        }

        return $changes;
    }
}

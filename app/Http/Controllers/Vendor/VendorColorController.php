<?php
// app/Http/Controllers/Vendor/VendorColorController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\ColorRequest;
use App\Traits\LogsVendorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorColorController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new Middleware('permission:view_colors', only: ['index', 'show']),
            // new Middleware('permission:request_colors', only: ['createRequest', 'storeRequest']),
        ];
    }

    /**
     * Display list of available colors (read-only for vendors)
     */
    public function index(Request $request)
    {
        $query = Color::where('status', true)
            ->where('approval_status', 'approved');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by featured/popular
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'true');
        }
        if ($request->filled('popular')) {
            $query->where('is_popular', $request->popular === 'true');
        }

        $colors = $query->orderBy('order')->paginate(24);

        // Get vendor's pending requests count
        $pendingRequestsCount = ColorRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->count();

        return view('marketplace.pages.colors.index', compact('colors', 'pendingRequestsCount'));
    }

    /**
     * Show single color details
     */
    public function show($id)
    {
        $color = Color::where('status', true)
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        return view('marketplace.pages.colors.show', compact('color'));
    }

    /**
     * Show form to request a new color
     */
    public function createRequest()
    {
        return view('marketplace.pages.colors.request');
    }

    /**
     * Store a new color request
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:color_requests,requested_name',
            'code' => 'required|string|max:7',
            'description' => 'nullable|string|max:1000',
            'reason' => 'required|string|max:500',
        ]);

        // Check if color already exists
        $existingColor = Color::where('code', $request->code)->first();
        if ($existingColor) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A color with this hex code already exists in our system.'
                ], 422);
            }
            return back()->with('error', 'A color with this hex code already exists in our system.');
        }

        // Check if color name already exists
        $existingColorName = Color::where('name', $request->name)->first();
        if ($existingColorName) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A color with this name already exists in our system.'
                ], 422);
            }
            return back()->with('error', 'A color with this name already exists in our system.');
        }

        // Check if pending request already exists
        $existingRequest = ColorRequest::where('vendor_id', auth('vendor')->id())
            ->where(function ($q) use ($request) {
                $q->where('requested_name', $request->name)
                    ->orWhere('requested_code', $request->code);
            })
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this color.'
                ], 422);
            }
            return back()->with('error', 'You already have a pending request for this color.');
        }

        $colorRequest = ColorRequest::create([
            'vendor_id' => auth('vendor')->id(),
            'requested_name' => $request->name,
            'requested_slug' => Str::slug($request->name),
            'requested_code' => strtoupper($request->code),
            'requested_rgb' => $request->rgb ?? null,
            'requested_hsl' => $request->hsl ?? null,
            'description' => $request->description,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        // Prepare new values for logging
        $newValues = [
            'id' => $colorRequest->id,
            'requested_name' => $colorRequest->requested_name,
            'requested_slug' => $colorRequest->requested_slug,
            'requested_code' => $colorRequest->requested_code,
            'requested_rgb' => $colorRequest->requested_rgb,
            'requested_hsl' => $colorRequest->requested_hsl,
            'description' => $colorRequest->description,
            'reason' => $colorRequest->reason,
            'status' => $colorRequest->status,
            'vendor_id' => $colorRequest->vendor_id,
            'created_at' => $colorRequest->created_at ? $colorRequest->created_at->toDateTimeString() : null,
        ];

        // Log activity using the logActivity method
        $this->logActivity(
            'request_color',                             // action
            'color_request',                             // entity_type
            $colorRequest->id,                           // entity_id
            $colorRequest->requested_name,               // entity_name
            null,                                        // old_values
            $newValues,                                  // new_values
            "Requested new color: {$colorRequest->requested_name} ({$colorRequest->requested_code}) - Reason: " . Str::limit($request->reason, 100)
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Color request submitted successfully. Admin will review it.',
                'request' => $colorRequest
            ]);
        }

        return redirect()->route('vendor.colors.requests.index')
            ->with('success', 'Color request submitted successfully. Admin will review it.');
    }

    /**
     * Display vendor's color requests
     */
    public function myRequests(Request $request)
    {
        $query = ColorRequest::where('vendor_id', auth('vendor')->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => ColorRequest::where('vendor_id', auth('vendor')->id())->count(),
            'pending' => ColorRequest::where('vendor_id', auth('vendor')->id())->where('status', 'pending')->count(),
            'approved' => ColorRequest::where('vendor_id', auth('vendor')->id())->where('status', 'approved')->count(),
            'rejected' => ColorRequest::where('vendor_id', auth('vendor')->id())->where('status', 'rejected')->count(),
        ];

        return view('marketplace.pages.colors.my-requests', compact('requests', 'statistics'));
    }

    /**
     * Show single request details
     */
    public function showRequest($id)
    {
        $request = ColorRequest::where('vendor_id', auth('vendor')->id())
            ->with('vendor', 'approvedBy', 'createdColor')
            ->findOrFail($id);

        return view('marketplace.pages.colors.request-details', compact('request'));
    }

    /**
     * Cancel a pending request
     */
    public function cancelRequest($id)
    {
        $colorRequest = ColorRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $oldValues = $colorRequest->toArray();
        $requestName = $colorRequest->requested_name;
        $colorRequest->delete();

        // Log activity using the logActivity method
        $this->logActivity(
            'cancel_color_request',                      // action
            'color_request',                             // entity_type
            $id,                                         // entity_id
            $requestName,                                // entity_name
            $oldValues,                                  // old_values
            null,                                        // new_values
            "Cancelled color request: {$requestName} ({$colorRequest->requested_code})"
        );

        return redirect()->route('vendor.colors.requests.index')
            ->with('success', 'Color request cancelled successfully.');
    }
}
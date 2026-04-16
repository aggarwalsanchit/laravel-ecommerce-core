<?php
// app/Http/Controllers/Vendor/VendorBrandController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandRequest;
use App\Models\Category;
use App\Traits\LogsVendorActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorBrandController extends Controller implements HasMiddleware
{
    use LogsVendorActivity;

    public static function middleware(): array
    {
        return [
            'auth:vendor',
            new Middleware('permission:view_brands', only: ['index', 'show']),
        ];
    }

    /**
     * Display list of available brands (read-only for vendors)
     */
    public function index(Request $request)
    {
        $query = Brand::with('categories')
            ->where('status', true);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        $brands = $query->orderBy('name')->paginate(20);

        // Get categories for filter
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        // Get vendor's pending requests count
        $pendingRequestsCount = BrandRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->count();

        return view('marketplace.pages.brands.index', compact('brands', 'categories', 'pendingRequestsCount'));
    }

    /**
     * Show single brand details
     */
    public function show($id)
    {
        $brand = Brand::with('categories')
            ->where('status', true)
            ->findOrFail($id);

        // Get products count for this brand
        $productsCount = $brand->products()->count();

        return view('marketplace.pages.brands.show', compact('brand', 'productsCount'));
    }

    /**
     * Show form to request a new brand
     */
    public function createRequest()
    {
        $categories = Category::where('status', true)
            ->where('approval_status', 'approved')
            ->where('parent_id', NULL)
            ->orderBy('name')
            ->get();

        return view('marketplace.pages.brands.request', compact('categories'));
    }

    /**
     * Store a new brand request
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brand_requests,requested_name',
            'code' => 'nullable|string|max:50|unique:brand_requests,requested_code',
            'description' => 'nullable|string|max:1000',
            'reason' => 'required|string|max:500',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Check if brand already exists
        $existingBrand = Brand::where('name', $request->name)->first();
        if ($existingBrand) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This brand already exists in our system.'
                ], 422);
            }
            return back()->with('error', 'This brand already exists in our system.');
        }

        // Check if pending request already exists
        $existingRequest = BrandRequest::where('vendor_id', auth('vendor')->id())
            ->where('requested_name', $request->name)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this brand.'
                ], 422);
            }
            return back()->with('error', 'You already have a pending request for this brand.');
        }

        DB::beginTransaction();

        try {
            $brandRequest = BrandRequest::create([
                'vendor_id' => auth('vendor')->id(),
                'requested_name' => $request->name,
                'requested_slug' => Str::slug($request->name),
                'requested_code' => $request->code ?? strtoupper(Str::slug($request->name, '')),
                'description' => $request->description,
                'reason' => $request->reason,
                'requested_category_ids' => $request->categories ?? [], // Store as JSON
                'status' => 'pending',
            ]);

            DB::commit();

            // Prepare new values for logging
            $newValues = [
                'id' => $brandRequest->id,
                'requested_name' => $brandRequest->requested_name,
                'requested_code' => $brandRequest->requested_code,
                'description' => $brandRequest->description,
                'reason' => $brandRequest->reason,
                'requested_category_ids' => $request->categories,
                'status' => $brandRequest->status,
                'vendor_id' => $brandRequest->vendor_id,
                'created_at' => $brandRequest->created_at ? $brandRequest->created_at->toDateTimeString() : null,
            ];

            // Log activity
            $this->logActivity(
                'request_brand',
                'brand_request',
                $brandRequest->id,
                $brandRequest->requested_name,
                null,
                $newValues,
                "Requested new brand: {$brandRequest->requested_name} - Reason: " . Str::limit($request->reason, 100)
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brand request submitted successfully. Admin will review it.',
                    'request' => $brandRequest
                ]);
            }

            return redirect()->route('vendor.brands.requests.index')
                ->with('success', 'Brand request submitted successfully. Admin will review it.');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display vendor's brand requests
     */
    public function myRequests(Request $request)
    {
        $query = BrandRequest::where('vendor_id', auth('vendor')->id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = [
            'total' => BrandRequest::where('vendor_id', auth('vendor')->id())->count(),
            'pending' => BrandRequest::where('vendor_id', auth('vendor')->id())->where('status', 'pending')->count(),
            'approved' => BrandRequest::where('vendor_id', auth('vendor')->id())->where('status', 'approved')->count(),
            'rejected' => BrandRequest::where('vendor_id', auth('vendor')->id())->where('status', 'rejected')->count(),
        ];

        return view('marketplace.pages.brands.my-requests', compact('requests', 'statistics'));
    }

    /**
     * Show single request details
     */
    public function showRequest($id)
    {
        $request = BrandRequest::where('vendor_id', auth('vendor')->id())
            ->with('vendor', 'approvedBy', 'createdBrand')
            ->findOrFail($id);

        // Get requested categories names
        $requestedCategories = [];
        if (!empty($request->requested_category_ids)) {
            $requestedCategories = Category::whereIn('id', $request->requested_category_ids)
                ->get();
        }

        return view('marketplace.pages.brands.request-details', compact('request', 'requestedCategories'));
    }

    /**
     * Cancel a pending request
     */
    public function cancelRequest($id)
    {
        $brandRequest = BrandRequest::where('vendor_id', auth('vendor')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $oldValues = $brandRequest->toArray();
        $requestName = $brandRequest->requested_name;

        $brandRequest->delete();

        // Log activity
        $this->logActivity(
            'cancel_brand_request',
            'brand_request',
            $id,
            $requestName,
            $oldValues,
            null,
            "Cancelled brand request: {$requestName}"
        );

        return redirect()->route('vendor.brands.requests.index')
            ->with('success', 'Brand request cancelled successfully.');
    }

    /**
     * Get brands by category for AJAX request
     */
    public function getBrandsByCategory($categoryId)
    {
        $brands = Brand::whereHas('categories', function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        })
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json([
            'success' => true,
            'brands' => $brands
        ]);
    }

    /**
     * Get all brands for AJAX request (for product form)
     */
    public function getAllBrands(Request $request)
    {
        $search = $request->get('search', '');

        $brands = Brand::where('status', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'code']);

        return response()->json([
            'results' => $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'text' => $brand->name . ' (' . $brand->code . ')'
                ];
            })
        ]);
    }
}

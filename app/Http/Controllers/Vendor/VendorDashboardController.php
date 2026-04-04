<?php
// app/Http/Controllers/Vendor/VendorDashboardController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public static function middleware(): array
    {
        return [
            'vendor.auth',
        ];
    }

    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        // Basic stats
        // $totalOrders = $vendor->orders()->count();
        // $totalRevenue = $vendor->orders()->where('status', 'delivered')->sum('total_amount');
        // $totalProducts = $vendor->products()->count();
        // $totalCustomers = $vendor->orders()->distinct('user_id')->count('user_id');

        // // Order status counts
        // $deliveredOrders = $vendor->orders()->where('status', 'delivered')->count();
        // $pendingOrders = $vendor->orders()->where('status', 'pending')->count();
        // $cancelledOrders = $vendor->orders()->where('status', 'cancelled')->count();

        // // Sales by period
        // $todaySales = $vendor->orders()->whereDate('created_at', today())->sum('total_amount');
        // $weekSales = $vendor->orders()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_amount');
        // $monthSales = $vendor->orders()->whereMonth('created_at', now()->month)->sum('total_amount');

        // // Recent orders
        // $recentOrders = $vendor->orders()->with('user')->latest()->limit(5)->get();

        // Top products
        // $topProducts = $vendor->products()
        //     ->withCount('orderItems')
        //     ->orderBy('order_items_count', 'desc')
        //     ->limit(5)
        //     ->get();

        // Chart data
        // $chartData = $this->getChartData($vendor);

        // Profile completion
        $profileCompletion = $vendor->profile_completed ?? 0;

        // Reviews
        // $totalReviews = $vendor->reviews()->count();
        // $averageRating = $vendor->reviews()->avg('rating') ?? 0;

        return view('marketplace.pages.dashboard', compact(
            'vendor',
            // 'totalOrders',
            // 'totalRevenue',
            // 'totalProducts',
            // 'totalCustomers',
            // 'deliveredOrders',
            // 'pendingOrders',
            // 'cancelledOrders',
            // 'todaySales',
            // 'weekSales',
            // 'monthSales',
            // 'recentOrders',
            // 'topProducts',
            // 'chartData',
            'profileCompletion',
            // 'totalReviews',
            // 'averageRating'
        ));
    }

    protected function getChartData($vendor)
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return date('M', mktime(0, 0, 0, $month, 1));
        });

        $revenue = collect(range(1, 12))->map(function ($month) use ($vendor) {
            return $vendor->orders()
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');
        });

        return [
            'labels' => $months,
            'revenue' => $revenue
        ];
    }

    public function salesReport(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $query = Order::where('vendor_id', $vendor->id);

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $totalRevenue = $query->sum('vendor_earning');
        $totalCommission = $query->sum('admin_commission');

        return view('marketplace.reports.sales', compact('orders', 'totalRevenue', 'totalCommission'));
    }

    public function payouts()
    {
        $vendor = Auth::user()->vendor;

        $payouts = $vendor->commissionLogs()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalEarnings = $vendor->commissionLogs()->sum('vendor_earning');
        $totalPaid = $vendor->commissionLogs()->where('is_paid', true)->sum('vendor_earning');
        $pendingAmount = $totalEarnings - $totalPaid;

        return view('marketplace.payouts', compact('payouts', 'totalEarnings', 'totalPaid', 'pendingAmount'));
    }
}

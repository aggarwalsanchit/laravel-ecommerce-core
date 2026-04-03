@extends('management.layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->
    <div class="page-content">
        <div class="page-container">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-head d-flex align-items-sm-center flex-sm-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-18 text-uppercase fw-bold m-0">Welcome back,
                                {{ Auth::guard('vendor')->user()->shop_name }}!</h4>
                            <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                        </div>
                        <div class="mt-3 mt-sm-0">
                            <div class="row g-2 mb-0 align-items-center">
                                <div class="col-auto">
                                    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
                                        <i class="ti ti-plus me-1"></i> Add New Product
                                    </a>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="input-group">
                                        <input type="text" class="form-control" data-provider="flatpickr"
                                            data-deafult-date="01 May to 31 May" data-date-format="d M"
                                            data-range-date="true">
                                        <span class="input-group-text bg-primary border-primary text-white">
                                            <i class="ti ti-calendar fs-15"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Status Alert -->
            @if (Auth::guard('vendor')->user()->verification_status !== 'verified')
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="ti ti-alert-circle me-2"></i>
                            <strong>Account Pending Verification!</strong> Your store is under review. Complete your profile
                            to get verified.
                            <a href="{{ route('vendor.complete-profile') }}" class="alert-link">Click here to complete
                                profile</a>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col">
                    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 text-center">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Total Orders">Total Orders</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-primary rounded-circle fs-22">
                                                <iconify-icon
                                                    icon="solar:case-round-minimalistic-bold-duotone"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold">{{ number_format($totalOrders ?? 0) }}</h3>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <span class="text-success me-2"><i class="ti ti-trending-up"></i>
                                            {{ $orderGrowth ?? 0 }}%</span>
                                        <span class="text-nowrap">vs last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Total Revenue">Total Revenue</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-success rounded-circle fs-22">
                                                <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <span class="text-success me-2"><i class="ti ti-trending-up"></i>
                                            {{ $revenueGrowth ?? 0 }}%</span>
                                        <span class="text-nowrap">vs last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Total Products">Total Products</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-info rounded-circle fs-22">
                                                <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold">{{ number_format($totalProducts ?? 0) }}</h3>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <span class="text-success me-2"><i class="ti ti-trending-up"></i>
                                            {{ $productGrowth ?? 0 }}%</span>
                                        <span class="text-nowrap">vs last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-muted fs-13 text-uppercase" title="Total Customers">Total Customers</h5>
                                    <div class="d-flex align-items-center justify-content-center gap-2 my-2 py-1">
                                        <div class="user-img fs-42 flex-shrink-0">
                                            <span class="avatar-title text-bg-warning rounded-circle fs-22">
                                                <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
                                            </span>
                                        </div>
                                        <h3 class="mb-0 fw-bold">{{ number_format($totalCustomers ?? 0) }}</h3>
                                    </div>
                                    <p class="mb-0 text-muted">
                                        <span class="text-success me-2"><i class="ti ti-trending-up"></i>
                                            {{ $customerGrowth ?? 0 }}%</span>
                                        <span class="text-nowrap">vs last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="header-title">Sales Overview</h4>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle drop-arrow-none card-drop"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:void(0);" class="dropdown-item">This Week</a>
                                    <a href="javascript:void(0);" class="dropdown-item">This Month</a>
                                    <a href="javascript:void(0);" class="dropdown-item">This Year</a>
                                </div>
                            </div>
                        </div>

                        <div class="bg-primary bg-opacity-10">
                            <div class="row text-center">
                                <div class="col-md-4 col-6">
                                    <p class="text-muted mt-3 mb-1">Today's Sales</p>
                                    <h4 class="mb-3">
                                        <span class="ti ti-trending-up text-success me-1"></span>
                                        <span>${{ number_format($todaySales ?? 0, 2) }}</span>
                                    </h4>
                                </div>
                                <div class="col-md-4 col-6">
                                    <p class="text-muted mt-3 mb-1">This Week</p>
                                    <h4 class="mb-3">
                                        <span class="ti ti-trending-up text-success me-1"></span>
                                        <span>${{ number_format($weekSales ?? 0, 2) }}</span>
                                    </h4>
                                </div>
                                <div class="col-md-4 col-6">
                                    <p class="text-muted mt-3 mb-1">This Month</p>
                                    <h4 class="mb-3">
                                        <span class="ti ti-chart-infographic me-1"></span>
                                        <span>${{ number_format($monthSales ?? 0, 2) }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div dir="ltr">
                                <div id="revenue-chart" class="apex-charts" data-colors="#6ac75a,#313a46"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4">
                    <div class="card">
                        <div
                            class="card-header d-flex justify-content-between align-items-center border-bottom border-dashed">
                            <h4 class="header-title">Order Status</h4>
                        </div>
                        <div class="card-body">
                            <div id="order-status-chart" class="apex-charts" data-colors="#6ac75a,#ffc107,#ef4444"></div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <i class="ti ti-circle-filled fs-10 text-success"></i>
                                        <span class="align-middle">Delivered</span>
                                    </div>
                                    <span class="fw-semibold">{{ $deliveredOrders ?? 0 }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <i class="ti ti-circle-filled fs-10 text-warning"></i>
                                        <span class="align-middle">Pending</span>
                                    </div>
                                    <span class="fw-semibold">{{ $pendingOrders ?? 0 }}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="ti ti-circle-filled fs-10 text-danger"></i>
                                        <span class="align-middle">Cancelled</span>
                                    </div>
                                    <span class="fw-semibold">{{ $cancelledOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                            <h4 class="header-title me-auto">Recent Orders</h4>
                            <a href="{{ route('vendor.orders') }}" class="btn btn-sm btn-primary">View All Orders</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom align-middle table-nowrap table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Product</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders ?? [] as $order)
                                            <tr>
                                                <td>#{{ $order->order_number }}</td>
                                                <td>{{ $order->customer_name }}</td>
                                                <td>{{ $order->product_count }} items</td>
                                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                                <td>
                                                    @if ($order->status == 'delivered')
                                                        <span class="badge badge-soft-success">Delivered</span>
                                                    @elseif($order->status == 'pending')
                                                        <span class="badge badge-soft-warning">Pending</span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge badge-soft-info">Processing</span>
                                                    @else
                                                        <span
                                                            class="badge badge-soft-danger">{{ ucfirst($order->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('vendor.orders.show', $order->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4">
                                                    <i class="ti ti-shopping-cart-off fs-1 text-muted"></i>
                                                    <p class="mt-2">No orders yet</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-7">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center gap-2 border-bottom border-dashed">
                            <h4 class="header-title me-auto">Top Selling Products</h4>
                            <a href="{{ route('vendor.products') }}" class="btn btn-sm btn-secondary">Manage Products</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-custom align-middle table-nowrap table-hover mb-0">
                                    <tbody>
                                        @forelse($topProducts ?? [] as $product)
                                            <tr>
                                                <td style="width: 60px;">
                                                    <div class="avatar-lg">
                                                        <img src="{{ $product->image_url ?? asset('assets/images/products/default.png') }}"
                                                            alt="{{ $product->name }}" class="img-fluid rounded-2">
                                                    </div>
                                                </td>
                                                <td class="ps-0">
                                                    <h5 class="fs-14 my-1">
                                                        <a href="{{ route('vendor.products.show', $product->id) }}"
                                                            class="link-reset">{{ $product->name }}</a>
                                                    </h5>
                                                    <span class="text-muted fs-12">SKU:
                                                        {{ $product->sku ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1">${{ number_format($product->price, 2) }}</h5>
                                                    <span class="text-muted fs-12">Price</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1">{{ $product->sold_count ?? 0 }}</h5>
                                                    <span class="text-muted fs-12">Units Sold</span>
                                                </td>
                                                <td>
                                                    <h5 class="fs-14 my-1">${{ number_format($product->revenue ?? 0, 2) }}
                                                    </h5>
                                                    <span class="text-muted fs-12">Revenue</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <i class="ti ti-package-off fs-1 text-muted"></i>
                                                    <p class="mt-2">No products yet. <a
                                                            href="{{ route('vendor.products.create') }}">Add your first
                                                            product</a></p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">Store Performance</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Profile Completion</span>
                                    <span class="fw-semibold">{{ $profileCompletion ?? 0 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $profileCompletion ?? 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Verification Status</span>
                                    <span class="fw-semibold">
                                        @if (Auth::guard('vendor')->user()->verification_status == 'verified')
                                            <span class="text-success">✓ Verified</span>
                                        @elseif(Auth::guard('vendor')->user()->verification_status == 'pending')
                                            <span class="text-warning">⏳ Pending</span>
                                        @else
                                            <span class="text-danger">✗ Rejected</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Reviews</span>
                                    <span class="fw-semibold">{{ $totalReviews ?? 0 }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">Rating:</div>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($averageRating ?? 0))
                                                <i class="ti ti-star-filled"></i>
                                            @elseif($i - 0.5 <= ($averageRating ?? 0))
                                                <i class="ti ti-star-half-filled"></i>
                                            @else
                                                <i class="ti ti-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ms-2">({{ number_format($averageRating ?? 0, 1) }}/5)</span>
                                </div>
                            </div>

                            <hr>

                            <div class="d-grid gap-2">
                                <a href="{{ route('vendor.complete-profile') }}" class="btn btn-outline-primary">
                                    <i class="ti ti-edit me-1"></i> Edit Store Profile
                                </a>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-outline-success">
                                    <i class="ti ti-plus me-1"></i> Add New Product
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('adminpanel/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        // Revenue Chart
        var revenueChartOptions = {
            series: [{
                name: 'Revenue',
                data: {!! json_encode($chartData['revenue'] ?? []) !!}
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: {!! json_encode($chartData['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!}
            },
            colors: ['#6ac75a'],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "$" + val.toFixed(2)
                    }
                }
            }
        };

        var revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), revenueChartOptions);
        revenueChart.render();

        // Order Status Chart
        var orderStatusOptions = {
            series: [{{ $deliveredOrders ?? 0 }}, {{ $pendingOrders ?? 0 }}, {{ $cancelledOrders ?? 0 }}],
            chart: {
                height: 250,
                type: 'donut'
            },
            labels: ['Delivered', 'Pending', 'Cancelled'],
            colors: ['#6ac75a', '#ffc107', '#ef4444'],
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            }
        };

        var orderStatusChart = new ApexCharts(document.querySelector("#order-status-chart"), orderStatusOptions);
        orderStatusChart.render();
    </script>
@endpush

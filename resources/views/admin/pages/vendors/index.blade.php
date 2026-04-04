{{-- resources/views/admin/vendors/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Vendor Management')

@section('content')
    <div class="page-content">
        <div class="page-container">
            <div class="page-title-head">
                <h4 class="fs-18 text-uppercase fw-bold mb-0">Vendor Management</h4>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Vendors</h6>
                            <h2>{{ $stats['total'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6>Pending Approval</h6>
                            <h2>{{ $stats['pending'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Verified Vendors</h6>
                            <h2>{{ $stats['verified'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Active Vendors</h6>
                            <h2>{{ $stats['active'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Create Own Store Button --}}
            <div class="mb-3">
                <form action="{{ route('admin.vendors.create-own-store') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-building-store"></i> Create/Update Own Store
                    </button>
                </form>
            </div>

            {{-- Vendors Table --}}
            <div class="card">
                <div class="card-header">
                    <h5>All Vendors</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Shop Name</th>
                                    <th>Owner</th>
                                    <th>Type</th>
                                    <th>Products</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Verification</th>
                                    <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($vendors as $vendor)
                                    <tr>
                                        <td>#{{ $vendor->id }}</td>
                                        <td>
                                            <strong>{{ $vendor->shop_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $vendor->shop_slug }}</small>
                                        </td>
                                        <td>{{ $vendor->user->name }}</td>
                                        <td>
                                            @if ($vendor->vendor_type === 'own_store')
                                                <span class="badge bg-success">Own Store</span>
                                            @else
                                                <span class="badge bg-info">Third Party</span>
                                            @endif
                                        </td>
                                        <td>{{ $vendor->total_products }}</td>
                                        <td>${{ number_format($vendor->total_revenue, 2) }}</td>
                                        <td>
                                            @if ($vendor->account_status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($vendor->account_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Suspended</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($vendor->verification_status === 'verified')
                                                <span class="badge bg-success">Verified</span>
                                            @elseif($vendor->verification_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="ti ti-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

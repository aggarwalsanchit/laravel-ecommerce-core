{{-- resources/views/admin/vendors/approve.blade.php --}}

@extends('management.layouts.app')

@section('title', 'Approve Vendor')

@section('content')
<div class="page-content">
    <div class="page-container">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Approve Vendor Application</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="ti ti-info-circle"></i>
                    <strong>Note:</strong> Approving this vendor will:
                    <ul class="mb-0 mt-2">
                        <li>Change role from <strong>Vendor</strong> to <strong>Store Owner</strong></li>
                        <li>Grant all permissions (products, orders, reports, staff management, etc.)</li>
                        <li>Activate the vendor account</li>
                        <li>Vendor will be able to access full marketplace features</li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('admin.vendors.approve', $vendor->id) }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Shop Name</label>
                                <input type="text" class="form-control" value="{{ $vendor->shop_name }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Owner Name</label>
                                <input type="text" class="form-control" value="{{ $vendor->name }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="{{ $vendor->shop_email }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" value="{{ $vendor->shop_phone }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Commission Rate (%)</label>
                                <input type="number" name="commission_rate" class="form-control" value="{{ $vendor->commission_rate ?? 10 }}" step="0.5">
                                <small class="text-muted">Commission rate for this vendor (default: 10%)</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Verification Notes (Optional)</label>
                                <textarea name="verification_notes" class="form-control" rows="3" placeholder="Add any notes about this verification..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check"></i> Approve & Upgrade to Store Owner
                        </button>
                        <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('admin.layout')

@section('title', 'Wastage')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-trash3 me-2"></i>
                Wastage
            </h3>

            <p class="text-muted mb-0">
                Manage damaged, expired and wasted inventory.
            </p>
        </div>

        <a href="{{ route('admin.inventory.wastage.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Add Wastage

        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.inventory.wastage.index') }}">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Wastage no. / Product">

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="approved"
                                {{ request('status') == 'approved' ? 'selected' : '' }}>
                                Approved
                            </option>

                            <option value="rejected"
                                {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Reason
                        </label>

                        <select name="reason"
                                class="form-select">

                            <option value="">
                                All Reasons
                            </option>

                            <option value="damaged">Damaged</option>
                            <option value="expired">Expired</option>
                            <option value="spillage">Spillage</option>
                            <option value="leakage">Leakage</option>
                            <option value="broken">Broken</option>
                            <option value="contaminated">Contaminated</option>
                            <option value="lost">Lost</option>
                            <option value="other">Other</option>

                        </select>

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-dark w-100">

                            <i class="bi bi-search me-1"></i>
                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Wastage Table --}}
    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Wastage Records
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Wastage No.</th>

                            <th>Date</th>

                            <th>Product</th>

                            <th>Branch</th>

                            <th>Qty</th>

                            <th>Total Cost</th>

                            <th>Reason</th>

                            <th>Status</th>

                            <th class="text-end">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($wastages as $wastage)

                        <tr>

                            <td>
                                {{ $wastages->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <strong>
                                    {{ $wastage->wastage_number }}
                                </strong>
                            </td>

                            <td>
                                {{ $wastage->wastage_date->format('d M Y') }}
                            </td>

                            <td>
                                {{ $wastage->product->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $wastage->branch->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $wastage->quantity }}
                            </td>

                            <td>
                                ₹{{ number_format($wastage->total_cost, 2) }}
                            </td>

                            <td>
                                {{ ucfirst($wastage->reason) }}
                            </td>

                            <td>

                                @if($wastage->status === 'pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($wastage->status === 'approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @endif

                            </td>

                            <td class="text-end">

                                <a href="{{ route('admin.inventory.wastage.show', $wastage->id) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10"
                                class="text-center py-5">

                                <i class="bi bi-trash3 fs-1 text-muted"></i>

                                <p class="text-muted mt-2 mb-0">
                                    No wastage records found.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($wastages->hasPages())

            <div class="card-footer">

                {{ $wastages->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
@extends('admin.layout')

@section('title', 'Coupons')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-ticket-perforated"></i>
                Coupons
            </h3>

            <p class="text-muted mb-0">
                Manage coupon codes and promotional offers.
            </p>
        </div>

        <a href="{{ route('admin.offers.coupons.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg"></i>
            Add Coupon

        </a>

    </div>


    {{-- Success --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.offers.coupons.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search Coupon
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Search coupon code...">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.offers.coupons.index') }}"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-clockwise"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Coupon Table --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-list-ul"></i>
                Coupon List

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Coupon Code</th>

                            <th>Discount</th>

                            <th>Min. Bill</th>

                            <th>Branch</th>

                            <th>Validity</th>

                            <th>Usage</th>

                            <th>Status</th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($coupons as $coupon)

                        <tr>

                            {{-- Number --}}
                            <td>
                                {{ $coupons->firstItem() + $loop->index }}
                            </td>


                            {{-- Code --}}
                            <td>

                                <strong class="text-primary">
                                    {{ $coupon->code }}
                                </strong>

                                @if($coupon->description)

                                    <div class="small text-muted">

                                        {{ \Illuminate\Support\Str::limit($coupon->description, 45) }}

                                    </div>

                                @endif

                            </td>


                            {{-- Discount --}}
                            <td>

                                @if($coupon->discount_type === 'percentage')

                                    <span class="badge bg-info">

                                        {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        ₹{{ number_format($coupon->discount_value, 2) }}

                                    </span>

                                @endif


                                @if($coupon->maximum_discount)

                                    <div class="small text-muted mt-1">

                                        Max:
                                        ₹{{ number_format($coupon->maximum_discount, 2) }}

                                    </div>

                                @endif

                            </td>


                            {{-- Minimum Bill --}}
                            <td>

                                @if($coupon->minimum_bill_amount > 0)

                                    ₹{{ number_format($coupon->minimum_bill_amount, 2) }}

                                @else

                                    <span class="text-muted">
                                        No Minimum
                                    </span>

                                @endif

                            </td>


                            {{-- Branch --}}
                            <td>

                                @if($coupon->branch)

                                    {{ $coupon->branch->name }}

                                @else

                                    <span class="text-muted">
                                        All Branches
                                    </span>

                                @endif

                            </td>


                            {{-- Validity --}}
                            <td>

                                @if($coupon->valid_from || $coupon->valid_until)

                                    <small>

                                        @if($coupon->valid_from)

                                            {{ \Carbon\Carbon::parse($coupon->valid_from)->format('d M Y') }}

                                        @else

                                            No Start

                                        @endif

                                        <br>

                                        <span class="text-muted">
                                            to
                                        </span>

                                        <br>

                                        @if($coupon->valid_until)

                                            {{ \Carbon\Carbon::parse($coupon->valid_until)->format('d M Y') }}

                                        @else

                                            No End

                                        @endif

                                    </small>

                                @else

                                    <span class="text-muted">
                                        No Limit
                                    </span>

                                @endif

                            </td>


                            {{-- Usage --}}
                            <td>

                                <strong>
                                    {{ $coupon->used_count }}
                                </strong>

                                @if($coupon->usage_limit)

                                    / {{ $coupon->usage_limit }}

                                @else

                                    <span class="text-muted">
                                        / Unlimited
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($coupon->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end">

                                <div class="d-inline-flex gap-1">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.offers.coupons.edit', $coupon) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- Toggle Status --}}
                                    <form method="POST"
                                          action="{{ route('admin.offers.coupons.toggle-status', $coupon) }}">

                                        @csrf

                                        @method('PATCH')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Toggle Status">

                                            @if($coupon->status === 'active')

                                                <i class="bi bi-toggle-on"></i>

                                            @else

                                                <i class="bi bi-toggle-off"></i>

                                            @endif

                                        </button>

                                    </form>


                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.offers.coupons.destroy', $coupon) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this coupon?');">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="text-center py-5">

                                <i class="bi bi-ticket-perforated fs-1 text-muted"></i>

                                <h5 class="mt-3">
                                    No coupons found
                                </h5>

                                <p class="text-muted">
                                    Create your first coupon.
                                </p>

                                <a href="{{ route('admin.offers.coupons.create') }}"
                                   class="btn btn-primary">

                                    <i class="bi bi-plus-lg"></i>
                                    Add Coupon

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($coupons->hasPages())

            <div class="card-footer">

                {{ $coupons->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
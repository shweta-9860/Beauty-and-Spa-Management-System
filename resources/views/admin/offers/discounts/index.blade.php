
@extends('admin.layout')

@section('title', 'Discounts')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-percent"></i> Discounts
            </h3>

            <p class="text-muted mb-0">
                Manage service, package and membership discounts.
            </p>
        </div>

        <a href="{{ route('admin.offers.discounts.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg"></i>
            Add Discount

        </a>

    </div>


    {{-- Success Message --}}
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


    {{-- Error Messages --}}
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
                  action="{{ route('admin.offers.discounts.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search discount..."
                               value="{{ request('search') }}">

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

                        <a href="{{ route('admin.offers.discounts.index') }}"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-clockwise"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Discounts Table --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-list-ul"></i>
                Discount List

            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Discount</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Applies To</th>
                            <th>Branch</th>
                            <th>Validity</th>
                            <th>Status</th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($discounts as $discount)

                        <tr>

                            {{-- ID --}}
                            <td>

                                {{ $discounts->firstItem() + $loop->index }}

                            </td>


                            {{-- Name --}}
                            <td>

                                <strong>
                                    {{ $discount->name }}
                                </strong>

                                @if($discount->description)

                                    <div class="small text-muted">

                                        {{ \Illuminate\Support\Str::limit($discount->description, 50) }}

                                    </div>

                                @endif

                            </td>


                            {{-- Type --}}
                            <td>

                                @if($discount->discount_type === 'percentage')

                                    <span class="badge bg-info">

                                        Percentage

                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">

                                        Fixed

                                    </span>

                                @endif

                            </td>


                            {{-- Value --}}
                            <td>

                                @if($discount->discount_type === 'percentage')

                                    <strong>

                                        {{ rtrim(rtrim(number_format($discount->discount_value, 2), '0'), '.') }}%

                                    </strong>

                                @else

                                    <strong>

                                        ₹{{ number_format($discount->discount_value, 2) }}

                                    </strong>

                                @endif

                            </td>


                            {{-- Applies To --}}
                            <td>

                                @switch($discount->applies_to)

                                    @case('service')

                                        Service

                                        @break

                                    @case('package')

                                        Package

                                        @break

                                    @case('membership')

                                        Membership

                                        @break

                                    @default

                                        All

                                @endswitch

                            </td>


                            {{-- Branch --}}
                            <td>

                                @if($discount->branch)

                                    {{ $discount->branch->name }}

                                @else

                                    <span class="text-muted">

                                        All Branches

                                    </span>

                                @endif

                            </td>


                            {{-- Validity --}}
                            <td>

                                @if($discount->start_date || $discount->end_date)

                                    <small>

                                        {{ $discount->start_date
                                            ? $discount->start_date->format('d M Y')
                                            : 'No Start'
                                        }}

                                        -

                                        {{ $discount->end_date
                                            ? $discount->end_date->format('d M Y')
                                            : 'No End'
                                        }}

                                    </small>

                                @else

                                    <span class="text-muted">

                                        No Limit

                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($discount->is_expired)

                                    <span class="badge bg-danger">

                                        Inactive

                                    </span>

                                @elseif($discount->is_active)

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
                                    <a href="{{ route('admin.offers.discounts.edit', $discount) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit">

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- Toggle --}}
                                    <form method="POST"
                                          action="{{ route('admin.offers.discounts.toggle-status', $discount) }}">

                                        @csrf

                                        @method('PATCH')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Toggle Status">

                                            @if($discount->is_active)

                                                <i class="bi bi-toggle-on"></i>

                                            @else

                                                <i class="bi bi-toggle-off"></i>

                                            @endif

                                        </button>

                                    </form>


                                    {{-- Delete --}}
                                    <form method="POST"
                                          action="{{ route('admin.offers.discounts.destroy', $discount) }}"

                                          onsubmit="return confirm('Are you sure you want to delete this discount?');">

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

                                <i class="bi bi-percent fs-1 text-muted"></i>

                                <h5 class="mt-3">

                                    No discounts found

                                </h5>

                                <p class="text-muted">

                                    Create your first discount.

                                </p>

                                <a href="{{ route('admin.offers.discounts.create') }}"
                                   class="btn btn-primary">

                                    <i class="bi bi-plus-lg"></i>

                                    Add Discount

                                </a>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($discounts->hasPages())

            <div class="card-footer">

                {{ $discounts->links() }}

            </div>

        @endif

    </div>

</div>

@endsection

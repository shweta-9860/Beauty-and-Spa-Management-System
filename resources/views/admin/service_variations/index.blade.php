
@extends('admin.layout')

@section('title', 'Service Variations | Salon Management')

@section('page-title', 'Service Variations')

@section('content')

<div class="container-fluid px-0">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Page Header --}}
    <div class="page-header mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Service Variations
            </h4>

            <p class="text-muted mb-0">
                Manage different variations, prices and durations for your services.
            </p>
        </div>

        <a href="{{ route('admin.service-variations.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Add Variation
        </a>

    </div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.service-variations.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Search variation or service..."
                            >

                        </div>

                    </div>


                    {{-- Service Filter --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Service
                        </label>

                        <select name="service_id"
                                class="form-select">

                            <option value="">
                                All Services
                            </option>

                            @foreach($services as $service)

                                <option
                                    value="{{ $service->id }}"
                                    {{ request('service_id') == $service->id ? 'selected' : '' }}
                                >
                                    {{ $service->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status Filter --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Filter Button --}}
                    <div class="col-lg-1 col-md-3">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="bi bi-funnel"></i>

                        </button>

                    </div>


                    {{-- Reset --}}
                    <div class="col-lg-2 col-md-3">

                        <a href="{{ route('admin.service-variations.index') }}"
                           class="btn btn-outline-secondary w-100">

                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Variation List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-0">
                        Variation List
                    </h5>

                    <small class="text-muted">
                        {{ $variations->total() }} Variations
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($variations->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    #
                                </th>

                                <th>
                                    Variation
                                </th>

                                <th>
                                    Service
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Duration
                                </th>

                                <th>
                                    Sort Order
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($variations as $variation)

                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4 fw-semibold">

                                        {{ $variations->firstItem() + $loop->index }}

                                    </td>


                                    {{-- Variation --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $variation->name }}
                                        </div>

                                        @if($variation->description)

                                            <small class="text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $variation->description,
                                                    55
                                                ) }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Service --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $variation->service->name ?? 'N/A' }}

                                        </span>

                                    </td>


                                    {{-- Price --}}
                                    <td>

                                        <strong>
                                            ₹{{ number_format($variation->price, 2) }}
                                        </strong>

                                    </td>


                                    {{-- Duration --}}
                                    <td>

                                        @if($variation->duration)

                                            <span>
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $variation->duration }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Sort Order --}}
                                    <td>

                                        <span class="badge bg-light text-dark border">

                                            {{ $variation->sort_order }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($variation->status)

                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="d-flex justify-content-end gap-2">

                                            {{-- Edit --}}
                                            <a
                                                href="{{ route(
                                                    'admin.service-variations.edit',
                                                    $variation
                                                ) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Edit"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            {{-- Toggle Status --}}
                                            <form
                                                action="{{ route(
                                                    'admin.service-variations.toggle-status',
                                                    $variation
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Toggle Status"
                                                >

                                                    @if($variation->status)
                                                        <i class="bi bi-toggle-on"></i>
                                                    @else
                                                        <i class="bi bi-toggle-off"></i>
                                                    @endif

                                                </button>

                                            </form>


                                            {{-- Delete --}}
                                            <form
                                                action="{{ route(
                                                    'admin.service-variations.destroy',
                                                    $variation
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to delete this variation?'
                                                );"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
                                                >

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($variations->hasPages())

                    <div class="card-footer bg-white border-0 py-3">

                        {{ $variations->links() }}

                    </div>

                @endif

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="empty-icon mb-3">

                        <i class="bi bi-diagram-3"></i>

                    </div>

                    <h5 class="fw-bold">
                        No Service Variations Found
                    </h5>

                    <p class="text-muted mb-3">
                        Start by adding your first service variation.
                    </p>

                    <a
                        href="{{ route('admin.service-variations.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>
                        Add Variation

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


@push('styles')

<style>

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .page-header .btn-primary {
        background: #d63384;
        border-color: #d63384;
    }

    .page-header .btn-primary:hover {
        background: #b82b70;
        border-color: #b82b70;
    }

    .card {
        border-radius: 14px;
    }

    .table th {
        font-size: 13px;
        font-weight: 700;
        color: #6c757d;
        white-space: nowrap;
    }

    .table td {
        font-size: 14px;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        margin: auto;
        border-radius: 50%;
        background: #f8e8f0;
        color: #d63384;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    @media (max-width: 768px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .btn {
            width: 100%;
        }

    }

</style>

@endpush

@endsection


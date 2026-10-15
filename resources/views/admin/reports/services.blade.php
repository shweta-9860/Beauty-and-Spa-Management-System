@extends('admin.layout')

@section('title', 'Service Report | Salon Management')

@section('page-title', 'Service Report')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1 fw-semibold">
                    <i class="bi bi-scissors me-2"></i>
                    Service List
                </h5>

                <small class="text-muted">
                    Total Records: {{ $services->total() }}
                </small>
            </div>

            <div class="d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    onclick="window.print()"
                >
                    <i class="bi bi-printer me-1"></i>
                    Print
                </button>

                <a
                    href="{{ route('admin.reports.services.pdf', request()->query()) }}"
                    class="btn btn-danger"
                >
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Download PDF
                </a>

            </div>
        </div>

        {{-- Filters --}}
        <div class="card-body border-bottom">

            <form method="GET"
                  action="{{ route('admin.reports.services') }}">

                <div class="row g-3">

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search service..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status" class="form-select">

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

                    <div class="col-md-4 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('admin.reports.services') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

        {{-- Service Table --}}
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th width="60">#</th>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($services as $service)

                            <tr>

                                <td>
                                    {{ $services->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $service->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $service->description ?? '-' }}
                                </td>

                                <td>

                                    @if($service->status === 'active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($service->status ?? 'Inactive') }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $service->created_at
                                        ? $service->created_at->format('d-m-Y')
                                        : '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    No services found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- Pagination --}}
        @if($services->hasPages())

            <div class="card-footer bg-white">
                {{ $services->links() }}
            </div>

        @endif

    </div>

</div>

{{-- Print CSS --}}
<style>

@media print {

    body {
        background: #fff !important;
    }

    .sidebar,
    .main-sidebar,
    .app-sidebar,
    .navbar,
    .btn,
    form,
    .card-footer {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-header {
        border: none !important;
    }

    .table {
        width: 100% !important;
    }

}

</style>

@endsection
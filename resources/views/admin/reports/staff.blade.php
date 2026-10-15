@extends('admin.layout')

@section('title', 'Staff Report | Salon Management')

@section('page-title', 'Staff Report')

@section('content')

<div class="container-fluid px-0">

    <div class="card shadow-sm border-0">

        {{-- Header --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-1 fw-semibold">
                    <i class="bi bi-people me-2"></i>
                    Staff List
                </h5>

                <small class="text-muted">
                    Total Records: {{ $staff->total() }}
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
                    href="{{ route('admin.reports.staff.pdf', request()->query()) }}"
                    class="btn btn-danger"
                >
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Download PDF
                </a>

            </div>

        </div>


        {{-- Filters --}}
        <div class="card-body border-bottom">

            <form
                method="GET"
                action="{{ route('admin.reports.staff') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Name, phone, email..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    {{-- Role --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Role
                        </label>

                        <select
                            name="role"
                            class="form-select"
                        >

                            <option value="">
                                All Roles
                            </option>

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role }}"
                                    {{ request('role') == $role ? 'selected' : '' }}
                                >
                                    {{ ucwords(str_replace('_', ' ', $role)) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search"></i>
                        </button>

                        <a
                            href="{{ route('admin.reports.staff') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- Staff Table --}}
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="55">#</th>

                            <th>Staff</th>

                            <th>Role</th>

                            <th>Specialization</th>

                            <th>Phone</th>

                            <th>Email</th>

                            <th>Commission</th>

                            <th>Joining Date</th>

                            <th>Branch</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($staff as $member)

                            <tr>

                                <td>
                                    {{ $staff->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $member->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $member->role
                                        ? ucwords(str_replace('_', ' ', $member->role))
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $member->specialization ?? '-' }}
                                </td>

                                <td>
                                    {{ $member->phone ?? '-' }}
                                </td>

                                <td>
                                    {{ $member->email ?? '-' }}
                                </td>

                                <td>

                                    @if($member->commission_type)

                                        {{ ucwords(str_replace('_', ' ', $member->commission_type)) }}

                                        @if($member->commission_value !== null)
                                            : {{ $member->commission_value }}
                                        @endif

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>
                                    {{ $member->joining_date
                                        ? \Carbon\Carbon::parse($member->joining_date)->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $member->branch->name ?? '-' }}
                                </td>

                                <td>

                                    @if($member->status === 'active')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($member->status ?? 'Inactive') }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-4 text-muted"
                                >

                                    <i class="bi bi-people fs-3 d-block mb-2"></i>

                                    No staff found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($staff->hasPages())

            <div class="card-footer bg-white">

                {{ $staff->links() }}

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
    form,
    .btn,
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
        font-size: 10px;
    }

}

</style>

@endsection
@extends('admin.layout')

@section('title', 'Reports | Salon Management')

@section('page-title', 'Reports')

@section('content')

<div class="container-fluid px-0">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">
            <i class="bi bi-bar-chart-line me-2"></i>
            Reports
        </h4>

        <p class="text-muted mb-0">
            View salon performance and operational reports.
        </p>
    </div>


    <div class="row g-4">

        {{-- Customer --}}
        <div class="col-xl-3 col-md-6">

            <a href="{{ route('admin.reports.customers') }}"
               class="text-decoration-none">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <i class="bi bi-people fs-1 text-primary"></i>

                        <h5 class="fw-bold mt-3">
                            Customer Report
                        </h5>

                        <p class="text-muted mb-0">
                            Customer registration and status report.
                        </p>

                    </div>

                </div>

            </a>

        </div>


        {{-- Coming Soon --}}
        @foreach([
            ['Appointment Report', 'bi-calendar-check'],
            ['Sales Report', 'bi-receipt'],
            ['Service Report', 'bi-scissors'],
            ['Staff & Commission', 'bi-person-badge'],
            ['Package Report', 'bi-box-seam'],
            ['Inventory Report', 'bi-boxes'],
            ['Expense Report', 'bi-cash-stack'],
        ] as $report)

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <i class="bi {{ $report[1] }} fs-1 text-secondary"></i>

                        <h5 class="fw-bold mt-3">
                            {{ $report[0] }}
                        </h5>

                        <span class="badge bg-secondary">
                            Coming Soon
                        </span>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection
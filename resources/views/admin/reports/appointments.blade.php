@extends('admin.layout')

@section('title', 'Appointment Report | Salon Management')
@section('page-title', 'Appointment Report')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-calendar-check me-2"></i>
                Appointment Report
            </h4>

            <p class="text-muted mb-0">
                Appointment List
            </p>
        </div>

        <div class="d-flex gap-2">

            {{-- Print --}}
            <button type="button"
                    onclick="window.print()"
                    class="btn btn-dark">

                <i class="bi bi-printer me-1"></i>
                Print

            </button>

            {{-- Download PDF --}}
            <a href="{{ route('admin.reports.appointments.pdf') }}"
               class="btn btn-danger">

                <i class="bi bi-file-earmark-pdf me-1"></i>
                Download PDF

            </a>

        </div>

    </div>


    {{-- Appointment List --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="fw-bold mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Appointment List
                </h6>

                <span class="badge bg-primary">
                    Total: {{ $appointments->count() }}
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Service</th>
                            <th>Staff</th>
                            <th>Branch</th>
                            <th>Status</th>
                        </tr>

                    </thead>


                    <tbody>

                        @forelse($appointments as $appointment)

                            <tr>

                                {{-- Number --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Date --}}
                                <td>
                                    @if($appointment->appointment_date)

                                        {{ $appointment->appointment_date->format('d-m-Y') }}

                                    @else

                                        -

                                    @endif
                                </td>


                                {{-- Time --}}
                                <td>

                                    @if($appointment->start_time)

                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}

                                    @else

                                        -

                                    @endif


                                    @if($appointment->end_time)

                                        -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}

                                    @endif

                                </td>


                                {{-- Customer --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $appointment->customer?->name ?? 'N/A' }}
                                    </div>

                                </td>


                                {{-- Phone --}}
                                <td>
                                    {{ $appointment->customer?->phone ?? '-' }}
                                </td>


                                {{-- Service --}}
                                <td>
                                    {{ $appointment->service?->name ?? 'N/A' }}
                                </td>


                                {{-- Staff --}}
                                <td>
                                    {{ $appointment->staff?->name ?? 'N/A' }}
                                </td>


                                {{-- Branch --}}
                                <td>
                                    {{ $appointment->branch?->name ?? 'N/A' }}
                                </td>


                                {{-- Status --}}
                                <td>

                                    @php

                                        $statusClass = match($appointment->status) {

                                            'pending'
                                                => 'bg-warning text-dark',

                                            'confirmed'
                                                => 'bg-info text-dark',

                                            'arrived'
                                                => 'bg-primary',

                                            'in_service'
                                                => 'bg-secondary',

                                            'completed'
                                                => 'bg-success',

                                            'cancelled'
                                                => 'bg-danger',

                                            'no_show'
                                                => 'bg-dark',

                                            default
                                                => 'bg-light text-dark',

                                        };

                                    @endphp


                                    <span class="badge {{ $statusClass }}">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $appointment->status
                                            )
                                        ) }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5 text-muted">

                                    <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>

                                    No appointments found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- Print CSS --}}
<style>

@media print {

    .sidebar,
    .app-sidebar,
    nav,
    .navbar,
    .btn,
    footer {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .container-fluid {
        width: 100% !important;
    }

    table {
        font-size: 11px;
    }

    @page {
        size: landscape;
        margin: 10mm;
    }

}

</style>

@endsection
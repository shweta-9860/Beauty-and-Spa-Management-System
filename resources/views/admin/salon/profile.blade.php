@extends('admin.layout')

@section('title', 'Salon Profile')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-shop"></i>
                Salon Profile
            </h3>

            <p class="text-muted mb-0">
                View all salon records
            </p>
        </div>

        <a
            href="{{ route('admin.salon.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle"></i>
            Add Salon
        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>
    @endif


    {{-- Error Message --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Salon Records Card --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="bi bi-table"></i>
                Salon Records
            </h5>

        </div>


        <div class="card-body p-0">

            @if($salons->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>ID</th>

                                <th>Logo</th>

                                <th>Salon Name</th>

                                <th>Owner</th>

                                <th>Phone</th>

                                <th>Email</th>

                                <th>Address</th>

                                <th>City</th>

                                <th>State</th>

                                <th>Pincode</th>

                                <th>GST Number</th>

                                <th>Opening Time</th>

                                <th>Closing Time</th>

                                <th>Currency</th>

                                <th>Tax %</th>

                                <th>Invoice Prefix</th>

                                <th>Receipt Footer</th>

                                <th>Appointment Duration</th>

                                <th>Status</th>

                                <th>Created At</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($salons as $salon)

                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $salon->id }}
                                    </td>


                                    {{-- Logo --}}
                                    <td>

                                        @if($salon->logo)

                                            <img
                                                src="{{ asset('storage/' . $salon->logo) }}"
                                                alt="{{ $salon->name }}"
                                                width="60"
                                                height="60"
                                                style="
                                                    object-fit: cover;
                                                    border-radius: 8px;
                                                "
                                            >

                                        @else

                                            <span class="text-muted">
                                                No Logo
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Salon Name --}}
                                    <td>
                                        <strong>
                                            {{ $salon->name }}
                                        </strong>
                                    </td>


                                    {{-- Owner --}}
                                    <td>

                                        @if($salon->owner)

                                            {{ $salon->owner->name }}

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Phone --}}
                                    <td>
                                        {{ $salon->phone ?? '-' }}
                                    </td>


                                    {{-- Email --}}
                                    <td>
                                        {{ $salon->email ?? '-' }}
                                    </td>


                                    {{-- Address --}}
                                    <td>
                                        {{ $salon->address ?? '-' }}
                                    </td>


                                    {{-- City --}}
                                    <td>
                                        {{ $salon->city ?? '-' }}
                                    </td>


                                    {{-- State --}}
                                    <td>
                                        {{ $salon->state ?? '-' }}
                                    </td>


                                    {{-- Pincode --}}
                                    <td>
                                        {{ $salon->pincode ?? '-' }}
                                    </td>


                                    {{-- GST --}}
                                    <td>
                                        {{ $salon->gst_number ?? '-' }}
                                    </td>


                                    {{-- Opening Time --}}
                                    <td>

                                        @if($salon->opening_time)

                                            {{ \Carbon\Carbon::parse($salon->opening_time)->format('h:i A') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Closing Time --}}
                                    <td>

                                        @if($salon->closing_time)

                                            {{ \Carbon\Carbon::parse($salon->closing_time)->format('h:i A') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Currency --}}
                                    <td>
                                        {{ $salon->currency }}
                                    </td>


                                    {{-- Tax --}}
                                    <td>
                                        {{ $salon->tax_percentage }}%
                                    </td>


                                    {{-- Invoice Prefix --}}
                                    <td>
                                        {{ $salon->invoice_prefix }}
                                    </td>


                                    {{-- Receipt Footer --}}
                                    <td>
                                        {{ $salon->receipt_footer ?? '-' }}
                                    </td>


                                    {{-- Appointment Duration --}}
                                    <td>
                                        {{ $salon->default_appointment_duration }}
                                        min
                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($salon->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Created At --}}
                                    <td>

                                        {{ $salon->created_at
                                            ? $salon->created_at->format('d-m-Y H:i')
                                            : '-'
                                        }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- No Records --}}
                <div class="text-center py-5">

                    <i
                        class="bi bi-shop"
                        style="font-size: 50px;"
                    ></i>

                    <h5 class="mt-3">
                        No Salon Records Found
                    </h5>

                    <p class="text-muted">
                        Add your first salon profile.
                    </p>

                    <a
                        href="{{ route('admin.salon.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-circle"></i>
                        Add Salon
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
@extends('admin.layout')

@section('title', 'Held Bills | Salon Management')
@section('page-title', 'Held Bills')

@section('content')

<div class="container-fluid px-0">

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-pause-circle me-2"></i>
                Held Bills
            </h4>

            <div class="text-muted">
                Bills saved for later completion
            </div>
        </div>

        <a
            href="{{ route('admin.billing.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle me-1"></i>
            New Bill
        </a>

    </div>


    {{-- HELD BILLS --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            @if($heldBills->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Invoice</th>

                                <th>Date</th>

                                <th>Customer</th>

                                <th>Branch</th>

                                <th>Services</th>

                                <th class="text-end">
                                    Total
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($heldBills as $bill)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $bill->invoice_number }}
                                        </strong>

                                    </td>


                                    <td>
                                        {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}
                                    </td>


                                    <td>

                                        @if($bill->customer)

                                            {{ $bill->customer->name }}

                                        @else

                                            <span class="text-muted">
                                                Walk-in Customer
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $bill->branch->name ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $bill->items->count() }}

                                        service(s)

                                    </td>


                                    <td class="text-end">

                                        <strong>
                                            ₹{{ number_format((float) $bill->grand_total, 2) }}
                                        </strong>

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-pause-circle me-1"></i>
                                            Held
                                        </span>

                                    </td>


                                    <td class="text-center">

                                        <a
                                            href="{{ route('admin.billing.resume', $bill->id) }}"
                                            class="btn btn-success btn-sm"
                                        >

                                            <i class="bi bi-play-circle me-1"></i>

                                            Resume

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-pause-circle"
                            style="font-size: 50px;"
                        ></i>

                    </div>

                    <h5>
                        No Held Bills
                    </h5>

                    <p class="text-muted mb-3">
                        There are currently no bills on hold.
                    </p>

                    <a
                        href="{{ route('admin.billing.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-plus-circle me-1"></i>
                        Create New Bill
                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
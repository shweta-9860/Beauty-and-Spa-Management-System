@extends('admin.layout')

@section('title', 'Purchase Details | Inventory')

@section('page-title', 'Purchase Details')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-receipt me-2"></i>
                Purchase Details
            </h4>

            <p class="text-muted mb-0">
                View complete purchase information.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.inventory.purchases.index') }}"
               class="btn btn-outline-secondary">

                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

    </div>


    {{-- Purchase Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-info-circle me-2"></i>
                Purchase Information
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- Purchase Number --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Purchase Number
                    </div>

                    <div class="fw-bold">
                        {{ $purchase->purchase_number ?? $purchase->id }}
                    </div>

                </div>


                {{-- Purchase Date --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Purchase Date
                    </div>

                    <div class="fw-semibold">

                        @if($purchase->purchase_date)
                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
                        @else
                            -
                        @endif

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Status
                    </div>

                    @php
                        $status = strtolower($purchase->status ?? 'pending');
                    @endphp

                    @if($status === 'received')

                        <span class="badge bg-success">
                            Received
                        </span>

                    @elseif($status === 'cancelled')

                        <span class="badge bg-danger">
                            Cancelled
                        </span>

                    @else

                        <span class="badge bg-warning text-dark">
                            {{ ucfirst($status) }}
                        </span>

                    @endif

                </div>


                {{-- Supplier --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Supplier
                    </div>

                    <div class="fw-semibold">

                        @if(isset($purchase->supplier))
                            {{ $purchase->supplier->name }}
                        @else
                            -
                        @endif

                    </div>

                </div>


                {{-- Branch --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Branch
                    </div>

                    <div class="fw-semibold">

                        @if(isset($purchase->branch))
                            {{ $purchase->branch->name }}
                        @else
                            -
                        @endif

                    </div>

                </div>


                {{-- Invoice Number --}}
                <div class="col-md-4">

                    <div class="text-muted small">
                        Invoice Number
                    </div>

                    <div class="fw-semibold">
                        {{ $purchase->invoice_number ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Purchase Items --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-box-seam me-2"></i>
                Purchased Items
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Product</th>

                            <th class="text-center">
                                Quantity
                            </th>

                            <th class="text-end">
                                Unit Price
                            </th>

                            <th class="text-end">
                                Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($purchase->items ?? [] as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        @if(isset($item->product))
                                            {{ $item->product->name }}
                                        @else
                                            {{ $item->product_name ?? $item->name ?? '-' }}
                                        @endif

                                    </div>

                                </td>


                                <td class="text-center">

                                    {{ $item->quantity ?? 0 }}

                                </td>


                                <td class="text-end">

                                    ₹{{ number_format((float)($item->unit_price ?? $item->price ?? 0), 2) }}

                                </td>


                                <td class="text-end fw-semibold">

                                    ₹{{ number_format((float)($item->total ?? (($item->quantity ?? 0) * ($item->unit_price ?? $item->price ?? 0))), 2) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center text-muted py-4">

                                    No purchase items found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- Purchase Summary --}}
    <div class="row justify-content-end">

        <div class="col-md-5">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-calculator me-2"></i>
                        Purchase Summary
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Subtotal
                        </span>

                        <span class="fw-semibold">

                            ₹{{ number_format((float)($purchase->subtotal ?? $purchase->total_amount ?? 0), 2) }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Discount
                        </span>

                        <span class="fw-semibold">

                            ₹{{ number_format((float)($purchase->discount ?? 0), 2) }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Tax
                        </span>

                        <span class="fw-semibold">

                            ₹{{ number_format((float)($purchase->tax_amount ?? 0), 2) }}

                        </span>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between">

                        <span class="fw-bold">
                            Grand Total
                        </span>

                        <span class="fw-bold fs-5">

                            ₹{{ number_format((float)($purchase->grand_total ?? $purchase->total_amount ?? $purchase->total ?? 0), 2) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if(!empty($purchase->notes))

        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-sticky me-2"></i>
                    Notes
                </h5>

            </div>

            <div class="card-body">

                {{ $purchase->notes }}

            </div>

        </div>

    @endif

</div>

@endsection
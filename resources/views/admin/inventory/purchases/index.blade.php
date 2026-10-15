@extends('admin.layout')

@section('title', 'Purchases')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-cart-check me-2"></i>
                Purchases
            </h4>

            <p class="text-muted mb-0">
                Manage inventory purchases and stock inward.
            </p>
        </div>

        <a href="{{ route('admin.inventory.purchases.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            New Purchase

        </a>

    </div>


    {{-- Purchase Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Purchase History
            </h5>

        </div>


        <div class="card-body p-0">

            @if($purchases->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Purchase No.</th>

                                <th>Date</th>

                                <th>Supplier</th>

                                <th>Branch</th>

                                <th class="text-end">
                                    Grand Total
                                </th>

                                <th>Payment</th>

                                <th>Status</th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($purchases as $purchase)

                                <tr>

                                    {{-- # --}}
                                    <td>
                                        {{ $purchases->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Purchase Number --}}
                                    <td>

                                        <strong>
                                            {{ $purchase->purchase_number }}
                                        </strong>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        {{ $purchase->purchase_date?->format('d M Y') }}

                                    </td>


                                    {{-- Supplier --}}
                                    <td>

                                        @if($purchase->supplier)

                                            {{ $purchase->supplier->name }}

                                        @else

                                            <span class="text-muted">
                                                No Supplier
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Branch --}}
                                    <td>

                                        @if($purchase->branch)

                                            {{ $purchase->branch->name }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Grand Total --}}
                                    <td class="text-end">

                                        <strong>
                                            ₹{{ number_format($purchase->grand_total, 2) }}
                                        </strong>

                                    </td>


                                    {{-- Payment --}}
                                    <td>

                                        @if($purchase->payment_status === 'paid')

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                        @elseif($purchase->payment_status === 'partial')

                                            <span class="badge bg-warning text-dark">
                                                Partial
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($purchase->status === 'received')

                                            <span class="badge bg-success">
                                                Received
                                            </span>

                                        @elseif($purchase->status === 'draft')

                                            <span class="badge bg-secondary">
                                                Draft
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-1">

                                            {{-- Show Details --}}
                                            <a href="{{ route('admin.inventory.purchases.show', $purchase->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Purchase Details">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <i class="bi bi-cart-x fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Purchases Found
                    </h5>

                    <p class="text-muted">
                        Start by creating your first inventory purchase.
                    </p>

                    <a href="{{ route('admin.inventory.purchases.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Create Purchase

                    </a>

                </div>

            @endif

        </div>


        {{-- Pagination --}}
        @if($purchases->hasPages())

            <div class="card-footer bg-white">

                {{ $purchases->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
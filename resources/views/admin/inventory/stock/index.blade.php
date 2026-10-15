@extends('admin.layout')

@section('title', 'Stock')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-box-seam"></i>
                Stock
            </h4>

            <p class="text-muted mb-0">
                Monitor current inventory stock.
            </p>
        </div>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        {{-- Total Products --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Total Products
                            </small>

                            <h3 class="mb-0 mt-1">
                                {{ $totalProducts }}
                            </h3>
                        </div>

                        <div class="text-primary fs-2">
                            <i class="bi bi-box-seam"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Available --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Available Stock
                            </small>

                            <h3 class="mb-0 mt-1 text-success">
                                {{ $availableStock }}
                            </h3>
                        </div>

                        <div class="text-success fs-2">
                            <i class="bi bi-check-circle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Low Stock --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Low Stock
                            </small>

                            <h3 class="mb-0 mt-1 text-warning">
                                {{ $lowStock }}
                            </h3>
                        </div>

                        <div class="text-warning fs-2">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Out of Stock --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <small class="text-muted">
                                Out of Stock
                            </small>

                            <h3 class="mb-0 mt-1 text-danger">
                                {{ $outOfStock }}
                            </h3>
                        </div>

                        <div class="text-danger fs-2">
                            <i class="bi bi-x-circle"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.inventory.stock.index') }}">

                <div class="row g-2">

                    <div class="col-md-6">

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Search product, SKU or brand..."
                               value="{{ request('search') }}">

                    </div>

                    <div class="col-md-4">

                        <select name="stock_status"
                                class="form-select">

                            <option value="">
                                All Stock
                            </option>

                            <option value="available"
                                {{ request('stock_status') == 'available' ? 'selected' : '' }}>
                                Available
                            </option>

                            <option value="low"
                                {{ request('stock_status') == 'low' ? 'selected' : '' }}>
                                Low Stock
                            </option>

                            <option value="out"
                                {{ request('stock_status') == 'out' ? 'selected' : '' }}>
                                Out of Stock
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button type="submit"
                                class="btn btn-primary w-100">

                            <i class="bi bi-search"></i>
                            Search

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Stock Table --}}
    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="bi bi-list"></i>
                Current Stock
            </h5>

            <span class="badge bg-secondary">
                {{ $products->total() }} Products
            </span>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Minimum Stock</th>
                            <th>Unit</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($products as $product)

                            @php
                                if ($product->stock <= 0) {
                                    $stockClass = 'danger';
                                    $stockText = 'Out of Stock';
                                } elseif ($product->stock <= $product->minimum_stock) {
                                    $stockClass = 'warning';
                                    $stockText = 'Low Stock';
                                } else {
                                    $stockClass = 'success';
                                    $stockText = 'Available';
                                }
                            @endphp

                            <tr>

                                <td>
                                    {{ $products->firstItem() + $loop->index }}
                                </td>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $product->name }}
                                    </div>

                                    @if($product->brand)
                                        <small class="text-muted">
                                            {{ $product->brand }}
                                        </small>
                                    @endif

                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $product->sku }}
                                    </span>
                                </td>

                                <td>
                                    {{ $product->category->name ?? '—' }}
                                </td>

                                <td>

                                    <strong>
                                        {{ rtrim(rtrim(number_format($product->stock, 2), '0'), '.') }}
                                    </strong>

                                </td>

                                <td>
                                    {{ rtrim(rtrim(number_format($product->minimum_stock, 2), '0'), '.') }}
                                </td>

                                <td>
                                    {{ ucfirst($product->unit) }}
                                </td>

                                <td>

                                    <span class="badge bg-{{ $stockClass }}">
                                        {{ $stockText }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-5">

                                    <i class="bi bi-box-seam fs-1 text-muted"></i>

                                    <h6 class="mt-3">
                                        No products found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Add products first to manage stock.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($products->hasPages())

            <div class="card-footer">

                {{ $products->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
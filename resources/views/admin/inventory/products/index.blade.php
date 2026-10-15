@extends('admin.layout')

@section('title', 'Products')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Products
            </h3>

            <p class="text-muted mb-0">
                Manage inventory products and stock
            </p>
        </div>

        <a href="{{ route('admin.inventory.products.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Add Product
        </a>
    </div>


    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- Products Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                All Products
            </h5>

            <span class="badge bg-secondary">
                {{ $products->total() }} Products
            </span>

        </div>


        <div class="card-body p-0">

            @if($products->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">#</th>

                                <th>Product</th>

                                <th>SKU</th>

                                <th>Category</th>

                                <th>Brand</th>

                                <th>Purchase Price</th>

                                <th>Selling Price</th>

                                <th>Stock</th>

                                <th>Min. Stock</th>

                                <th>Status</th>

                                <th width="130" class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($products as $product)

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        {{ $products->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Product --}}
                                    <td>
                                        <strong>
                                            {{ $product->name }}
                                        </strong>
                                    </td>


                                    {{-- SKU --}}
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $product->sku }}
                                        </span>
                                    </td>


                                    {{-- Category --}}
                                    <td>

                                        @if($product->category)
                                            {{ $product->category->name }}
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Brand --}}
                                    <td>
                                        {{ $product->brand ?: '—' }}
                                    </td>


                                    {{-- Purchase Price --}}
                                    <td>
                                        ₹{{ number_format($product->purchase_price, 2) }}
                                    </td>


                                    {{-- Selling Price --}}
                                    <td>
                                        ₹{{ number_format($product->selling_price, 2) }}
                                    </td>


                                    {{-- Stock --}}
                                    <td>

                                        @if($product->stock <= 0)

                                            <span class="badge bg-danger">
                                                Out of Stock
                                            </span>

                                        @elseif($product->stock <= $product->minimum_stock)

                                            <span class="badge bg-warning text-dark">
                                                {{ $product->stock }}
                                                {{ $product->unit }}
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                {{ $product->stock }}
                                                {{ $product->unit }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Minimum Stock --}}
                                    <td>
                                        {{ $product->minimum_stock }}
                                        {{ $product->unit }}
                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($product->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-center">

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.inventory.products.edit', $product) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form action="{{ route('admin.inventory.products.destroy', $product) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-box-seam fs-1 text-muted"></i>
                    </div>

                    <h5>
                        No Products Found
                    </h5>

                    <p class="text-muted">
                        Add your first inventory product.
                    </p>

                    <a href="{{ route('admin.inventory.products.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add Product

                    </a>

                </div>

            @endif

        </div>


        {{-- Pagination --}}
        @if($products->hasPages())

            <div class="card-footer bg-white">
                {{ $products->links() }}
            </div>

        @endif

    </div>

</div>
@endsection
@extends('admin.layout')

@section('title', 'Add Product')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-plus-circle me-2"></i>
                Add Product
            </h3>

            <p class="text-muted mb-0">
                Add a new product to inventory
            </p>
        </div>

        <a href="{{ route('admin.inventory.products.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                <i class="bi bi-box-seam me-2"></i>

                Product Details

            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route('admin.inventory.products.store') }}"
                  method="POST">

                @csrf

                <div class="row">

                    {{-- Product Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Product Name

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. Hair Shampoo">

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- SKU --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            SKU

                            <span class="text-danger">*</span>

                        </label>

                        <input type="text"
                               name="sku"
                               value="{{ old('sku') }}"
                               class="form-control @error('sku') is-invalid @enderror"
                               placeholder="e.g. SHP-001">

                        @error('sku')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Category --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('category_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Brand --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Brand
                        </label>

                        <input type="text"
                               name="brand"
                               value="{{ old('brand') }}"
                               class="form-control @error('brand') is-invalid @enderror"
                               placeholder="e.g. L'Oréal">

                        @error('brand')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Purchase Price --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Purchase Price

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="purchase_price"
                                   value="{{ old('purchase_price', 0) }}"
                                   class="form-control @error('purchase_price') is-invalid @enderror"
                                   placeholder="0.00">

                        </div>

                        @error('purchase_price')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Selling Price --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Selling Price

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="selling_price"
                                   value="{{ old('selling_price', 0) }}"
                                   class="form-control @error('selling_price') is-invalid @enderror"
                                   placeholder="0.00">

                        </div>

                        @error('selling_price')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Current Stock --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Current Stock

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="stock"
                               value="{{ old('stock', 0) }}"
                               class="form-control @error('stock') is-invalid @enderror">

                        @error('stock')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Minimum Stock --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Minimum Stock

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="minimum_stock"
                               value="{{ old('minimum_stock', 0) }}"
                               class="form-control @error('minimum_stock') is-invalid @enderror">

                        @error('minimum_stock')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Unit --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Unit

                            <span class="text-danger">*</span>

                        </label>

                        <select name="unit"
                                class="form-select @error('unit') is-invalid @enderror">

                            <option value="piece"
                                {{ old('unit', 'piece') == 'piece' ? 'selected' : '' }}>
                                Piece
                            </option>

                            <option value="box"
                                {{ old('unit') == 'box' ? 'selected' : '' }}>
                                Box
                            </option>

                            <option value="bottle"
                                {{ old('unit') == 'bottle' ? 'selected' : '' }}>
                                Bottle
                            </option>

                            <option value="pack"
                                {{ old('unit') == 'pack' ? 'selected' : '' }}>
                                Pack
                            </option>

                            <option value="kg"
                                {{ old('unit') == 'kg' ? 'selected' : '' }}>
                                Kg
                            </option>

                            <option value="gram"
                                {{ old('unit') == 'gram' ? 'selected' : '' }}>
                                Gram
                            </option>

                            <option value="litre"
                                {{ old('unit') == 'litre' ? 'selected' : '' }}>
                                Litre
                            </option>

                            <option value="ml"
                                {{ old('unit') == 'ml' ? 'selected' : '' }}>
                                ML
                            </option>

                        </select>

                        @error('unit')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Supplier --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Supplier
                        </label>

                        <select name="supplier_id"
                                id="supplier_id"
                                class="form-select @error('supplier_id') is-invalid @enderror">

                            <option value="">
                                Select Supplier
                            </option>

                            @foreach($suppliers as $supplier)

                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('supplier_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        @if($suppliers->isEmpty())

                            <small class="text-danger">
                                No suppliers found for this salon.
                            </small>

                        @else

                            <small class="text-muted">
                                Select the supplier for this product.
                            </small>

                        @endif

                    </div>


                    {{-- Expiry Date --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input type="date"
                               name="expiry_date"
                               value="{{ old('expiry_date') }}"
                               class="form-control @error('expiry_date') is-invalid @enderror">

                        @error('expiry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input type="hidden"
                                   name="status"
                                   value="0">

                            <input type="checkbox"
                                   name="status"
                                   value="1"
                                   id="status"
                                   class="form-check-input"
                                   checked>

                            <label class="form-check-label"
                                   for="status">

                                Active

                            </label>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-3">

                    <a href="{{ route('admin.inventory.products.index') }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>

                        Save Product

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
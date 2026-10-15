@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Edit Product</h4>
            <p class="text-muted mb-0">
                Update product details and stock information.
            </p>
        </div>

        <a href="{{ route('admin.inventory.products.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-box-seam"></i>
                Product Information
            </h5>
        </div>

        <form action="{{ route('admin.inventory.products.update', $product->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="card-body">

                <div class="row">

                    {{-- Product Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Product Name <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $product->name) }}"
                               required>
                    </div>

                    {{-- SKU --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            SKU <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                               name="sku"
                               class="form-control"
                               value="{{ old('sku', $product->sku) }}"
                               required>

                        <small class="text-muted">
                            SKU must be unique within the salon.
                        </small>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Category
                        </label>

                        <select name="category_id" class="form-select">
                            <option value="">-- Select Category --</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Brand
                        </label>

                        <input type="text"
                               name="brand"
                               class="form-control"
                               value="{{ old('brand', $product->brand) }}">
                    </div>

                    {{-- Purchase Price --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Purchase Price <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">₹</span>

                            <input type="number"
                                   name="purchase_price"
                                   class="form-control"
                                   value="{{ old('purchase_price', $product->purchase_price) }}"
                                   min="0"
                                   step="0.01"
                                   required>
                        </div>
                    </div>

                    {{-- Selling Price --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Selling Price <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">₹</span>

                            <input type="number"
                                   name="selling_price"
                                   class="form-control"
                                   value="{{ old('selling_price', $product->selling_price) }}"
                                   min="0"
                                   step="0.01"
                                   required>
                        </div>
                    </div>

                    {{-- Current Stock --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Current Stock <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="stock"
                               class="form-control"
                               value="{{ old('stock', $product->stock) }}"
                               min="0"
                               step="0.01"
                               required>

                        <small class="text-muted">
                            Later this will also be updated through Purchases
                            and Stock Adjustments.
                        </small>
                    </div>

                    {{-- Minimum Stock --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Minimum Stock <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="minimum_stock"
                               class="form-control"
                               value="{{ old('minimum_stock', $product->minimum_stock) }}"
                               min="0"
                               step="0.01"
                               required>

                        <small class="text-muted">
                            Low-stock alert will use this value.
                        </small>
                    </div>

                    {{-- Unit --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Unit <span class="text-danger">*</span>
                        </label>

                        <select name="unit"
                                class="form-select"
                                required>

                            @php
                                $units = [
                                    'piece' => 'Piece',
                                    'box' => 'Box',
                                    'bottle' => 'Bottle',
                                    'pack' => 'Pack',
                                    'kg' => 'Kilogram',
                                    'gram' => 'Gram',
                                    'litre' => 'Litre',
                                    'ml' => 'Millilitre',
                                ];
                            @endphp

                            @foreach ($units as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('unit', $product->unit) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Supplier --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Supplier
                        </label>

                        <select name="supplier_id"
                                class="form-select">

                            <option value="">-- Select Supplier --</option>

                            {{-- Supplier module will be connected later --}}

                        </select>

                        <small class="text-muted">
                            Supplier selection will be connected after
                            Supplier module is created.
                        </small>
                    </div>

                    {{-- Expiry Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Expiry Date
                        </label>

                        <input type="date"
                               name="expiry_date"
                               class="form-control"
                               value="{{ old(
                                   'expiry_date',
                                   optional($product->expiry_date)->format('Y-m-d')
                               ) }}">
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">
                            Status
                        </label>

                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="status"
                                   value="1"
                                   id="status"
                                   {{ old('status', $product->status) ? 'checked' : '' }}>

                            <label class="form-check-label" for="status">
                                Active
                            </label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-footer d-flex justify-content-end gap-2">

                <a href="{{ route('admin.inventory.products.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Update Product
                </button>

            </div>

        </form>
    </div>

</div>
@endsection
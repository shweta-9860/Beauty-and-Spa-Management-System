@extends('admin.layout')

@section('title', 'Edit Product Category')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Product Category
            </h3>

            <p class="text-muted mb-0">
                Update product category details
            </p>
        </div>

        <a href="{{ route('admin.inventory.categories.index') }}"
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


    {{-- Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="bi bi-tags me-2"></i>
                Category Details
            </h5>

        </div>


        <div class="card-body">

            <form
                action="{{ route('admin.inventory.categories.update', $productCategory) }}"
                method="POST">

                @csrf
                @method('PUT')


                <div class="row">

                    {{-- Category Name --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Category Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $productCategory->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Hair Care">

                        @error('name')
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

                            <input
                                type="hidden"
                                name="status"
                                value="0">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                id="status"
                                class="form-check-input"
                                {{ old('status', $productCategory->status) ? 'checked' : '' }}>

                            <label
                                class="form-check-label"
                                for="status">

                                Active

                            </label>

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter category description">{{ old('description', $productCategory->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.inventory.categories.index') }}"
                        class="btn btn-light">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Update Category

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection
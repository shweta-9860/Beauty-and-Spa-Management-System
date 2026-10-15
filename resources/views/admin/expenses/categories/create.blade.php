@extends('admin.layout')

@section('title', 'Add Expense Category | Salon Management')
@section('page-title', 'Add Expense Category')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Add Expense Category
            </h4>

            <p class="text-muted mb-0">
                Create a new category for salon expenses.
            </p>
        </div>

        <a href="{{ route('admin.expenses.categories.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">
                Category Details
            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route('admin.expenses.categories.store') }}"
                  method="POST">

                @csrf


                {{-- Category Name --}}
                <div class="mb-3">

                    <label for="name"
                           class="form-label fw-semibold">

                        Category Name
                        <span class="text-danger">*</span>

                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           placeholder="e.g. Rent, Electricity, Marketing"
                           required>

                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Description --}}
                <div class="mb-3">

                    <label for="description"
                           class="form-label fw-semibold">

                        Description

                    </label>

                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Enter category description">{{ old('description') }}</textarea>

                    @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Status --}}
                <div class="mb-4">

                    <div class="form-check form-switch">

                        <input type="hidden"
                               name="status"
                               value="0">

                        <input type="checkbox"
                               name="status"
                               id="status"
                               value="1"
                               class="form-check-input"
                               {{ old('status', true) ? 'checked' : '' }}>

                        <label for="status"
                               class="form-check-label fw-semibold">

                            Active

                        </label>

                    </div>

                    <small class="text-muted">
                        Active categories can be selected while creating expenses.
                    </small>

                </div>


                {{-- Buttons --}}
                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>
                        Save Category

                    </button>

                    <a href="{{ route('admin.expenses.categories.index') }}"
                       class="btn btn-light border">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
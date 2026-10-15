
@extends('admin.layout')

@section('title', 'Add Service Variation | Salon Management')

@section('page-title', 'Add Service Variation')

@section('content')

<div class="container-fluid px-0">

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="page-header mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Add Service Variation
            </h4>

            <p class="text-muted mb-0">
                Add a variation with its own price and duration.
            </p>
        </div>

        <a href="{{ route('admin.service-variations.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Form Card --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form
                action="{{ route('admin.service-variations.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-4">

                    {{-- Service --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Service
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="service_id"
                            class="form-select @error('service_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Service
                            </option>

                            @foreach($services as $service)

                                <option
                                    value="{{ $service->id }}"
                                    {{ old('service_id', $selectedService?->id) == $service->id ? 'selected' : '' }}
                                >
                                    {{ $service->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('service_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Variation Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Variation Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Men Hair Cut"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Price --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Price
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input
                                type="number"
                                name="price"
                                value="{{ old('price') }}"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="300"
                                min="0"
                                step="0.01"
                                required
                            >

                        </div>

                        @error('price')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Duration --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Duration
                        </label>

                        <input
                            type="text"
                            name="duration"
                            value="{{ old('duration') }}"
                            class="form-control @error('duration') is-invalid @enderror"
                            placeholder="e.g. 30 Minutes"
                        >

                        @error('duration')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Sort Order --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Sort Order
                        </label>

                        <input
                            type="number"
                            name="sort_order"
                            value="{{ old('sort_order', 0) }}"
                            class="form-control @error('sort_order') is-invalid @enderror"
                            min="0"
                        >

                        @error('sort_order')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter variation description..."
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="1"
                                {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a
                        href="{{ route('admin.service-variations.index') }}"
                        class="btn btn-light border"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Save Variation
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('styles')

<style>

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .card {
        border-radius: 14px;
    }

    .form-label {
        color: #40353a;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border-radius: 8px;
    }

    textarea.form-control {
        min-height: 110px;
    }

    .btn-primary {
        background: #d63384;
        border-color: #d63384;
    }

    .btn-primary:hover {
        background: #b82b70;
        border-color: #b82b70;
    }

    @media (max-width: 768px) {

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header .btn {
            width: 100%;
        }

    }

</style>

@endpush

@endsection


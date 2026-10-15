
@extends('admin.layout')

@section('title', 'Edit Service Variation | Salon Management')

@section('page-title', 'Edit Service Variation')

@section('content')

<div class="variation-page">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="bi bi-exclamation-circle me-2"></i>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h2>
                <i class="bi bi-pencil-square me-2"></i>
                Edit Service Variation
            </h2>

            <p>
                Update service variation details
            </p>
        </div>

        <a
            href="{{ route('admin.service-variations.index') }}"
            class="btn-back"
        >
            <i class="bi bi-arrow-left me-2"></i>
            Back to Variations
        </a>

    </div>


    {{-- Form Card --}}
    <div class="form-card">

        <form
            action="{{ route('admin.service-variations.update', $serviceVariation->id) }}"
            method="POST"
        >

            @csrf

            @method('PUT')


            <div class="row g-4">

                {{-- Service --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Service <span class="required">*</span>
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
                                {{ old('service_id', $serviceVariation->service_id) == $service->id ? 'selected' : '' }}
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

                    <label class="form-label">
                        Variation Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $serviceVariation->name) }}"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Example: Men Hair Cut"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Price --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Price <span class="required">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            ₹
                        </span>

                        <input
                            type="number"
                            name="price"
                            value="{{ old('price', $serviceVariation->price) }}"
                            class="form-control @error('price') is-invalid @enderror"
                            placeholder="0.00"
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
                <div class="col-md-6">

                    <label class="form-label">
                        Duration
                    </label>

                    <input
                        type="text"
                        name="duration"
                        value="{{ old('duration', $serviceVariation->duration) }}"
                        class="form-control @error('duration') is-invalid @enderror"
                        placeholder="Example: 30 Minutes"
                    >

                    @error('duration')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Sort Order --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Sort Order
                    </label>

                    <input
                        type="number"
                        name="sort_order"
                        value="{{ old('sort_order', $serviceVariation->sort_order) }}"
                        class="form-control @error('sort_order') is-invalid @enderror"
                        min="0"
                        placeholder="0"
                    >

                    <div class="form-text">
                        Lower number appears first.
                    </div>

                    @error('sort_order')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Status --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Status <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select @error('status') is-invalid @enderror"
                        required
                    >

                        <option
                            value="1"
                            {{ old('status', $serviceVariation->status) == 1 ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            {{ old('status', $serviceVariation->status) == 0 ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                    @error('status')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                {{-- Description --}}
                <div class="col-12">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Enter variation description..."
                    >{{ old('description', $serviceVariation->description) }}</textarea>

                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- Form Actions --}}
            <div class="form-actions">

                <a
                    href="{{ route('admin.service-variations.index') }}"
                    class="btn-cancel"
                >
                    <i class="bi bi-x-lg me-1"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-update"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Update Variation
                </button>

            </div>

        </form>

    </div>

</div>


<style>

/* =========================================
   Page
========================================= */

.variation-page {
    padding: 10px 0 30px;
}


/* =========================================
   Alerts
========================================= */

.variation-page .alert {
    border: none;
    border-radius: 12px;
    margin-bottom: 20px;
}


/* =========================================
   Header
========================================= */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 25px;
}

.page-header h2 {
    margin: 0;
    color: #2d2529;
    font-size: 25px;
    font-weight: 700;
}

.page-header p {
    margin: 6px 0 0;
    color: #8b7d82;
    font-size: 14px;
}


/* =========================================
   Back Button
========================================= */

.btn-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 9px;
    background: #f7edf1;
    color: #8b5e6d;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: 0.2s ease;
}

.btn-back:hover {
    background: #8b5e6d;
    color: #ffffff;
}


/* =========================================
   Form Card
========================================= */

.form-card {
    background: #ffffff;
    border: 1px solid #eee5e8;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 3px 15px rgba(45, 37, 41, 0.04);
}


/* =========================================
   Labels
========================================= */

.form-label {
    color: #514449;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 7px;
}

.required {
    color: #c75b5b;
}


/* =========================================
   Inputs
========================================= */

.form-control,
.form-select {
    min-height: 44px;
    border-radius: 9px;
    border: 1px solid #ddd2d7;
    color: #40373b;
    font-size: 13px;
}

.form-control:focus,
.form-select:focus {
    border-color: #8b5e6d;
    box-shadow: 0 0 0 0.2rem rgba(139, 94, 109, 0.12);
}

textarea.form-control {
    min-height: 120px;
    resize: vertical;
}

.input-group-text {
    min-height: 44px;
    background: #f7f1f3;
    border: 1px solid #ddd2d7;
    color: #8b5e6d;
    font-weight: 700;
}


/* =========================================
   Help Text
========================================= */

.form-text {
    color: #9a8c91;
    font-size: 11px;
    margin-top: 5px;
}


/* =========================================
   Actions
========================================= */

.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid #eee5e8;
}

.btn-cancel,
.btn-update {
    min-height: 42px;
    padding: 10px 18px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-cancel {
    background: #f2edef;
    color: #74656b;
}

.btn-cancel:hover {
    background: #e7dfe3;
    color: #514449;
}

.btn-update {
    background: #8b5e6d;
    color: #ffffff;
}

.btn-update:hover {
    background: #704956;
    color: #ffffff;
}


/* =========================================
   Responsive
========================================= */

@media (max-width: 767px) {

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .btn-back {
        width: 100%;
    }

    .form-card {
        padding: 18px;
    }

    .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .btn-cancel,
    .btn-update {
        width: 100%;
    }

}

</style>

@endsection


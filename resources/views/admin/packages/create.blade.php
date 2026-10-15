
@extends('admin.layout')

@section('title', 'Add Package | Salon Management')

@section('page-title', 'Add Package')

@section('content')

<div class="container-fluid px-0">

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Add Package
            </h4>

            <p class="text-muted mb-0">
                Create a new package for your salon.
            </p>
        </div>

        <a href="{{ route('admin.packages.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Packages
        </a>
    </div>

    {{-- Package Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square me-2"></i>
                Package Details
            </h5>
        </div>

        <div class="card-body">

            <form action="{{ route('admin.packages.store') }}"
                  method="POST"
                  id="packageForm">

                @csrf

                {{-- =====================================================
                    BASIC INFORMATION
                ====================================================== --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Basic Information
                    </h6>

                    <div class="row g-3">

                        {{-- Package Name --}}
                        <div class="col-md-6">

                            <label for="name" class="form-label">
                                Package Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Bridal Package"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        {{-- Package Code --}}
                        <div class="col-md-6">

                            <label for="code" class="form-label">
                                Package Code
                            </label>

                            <input type="text"
                                   name="code"
                                   id="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="e.g. BRIDAL-001">

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Optional unique package code.
                            </small>

                        </div>

                        {{-- Description --}}
                        <div class="col-12">

                            <label for="description" class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter package description...">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>
                </div>


                <hr class="my-4">


                {{-- =====================================================
                    PACKAGE SERVICES
                ====================================================== --}}
                <div class="mb-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h6 class="fw-semibold mb-1">
                                <i class="bi bi-scissors me-2"></i>
                                Package Services
                            </h6>

                            <small class="text-muted">
                                Add services included in this package.
                            </small>
                        </div>

                    </div>


                    {{-- Add Service Row --}}
                    <div class="card border bg-light">

                        <div class="card-body">

                            <div class="row g-3 align-items-end">

                                {{-- Service --}}
                                <div class="col-md-5">

                                    <label for="service_id" class="form-label">
                                        Service
                                    </label>

                                    <select id="service_id"
                                            class="form-select">

                                        <option value="">
                                            Select Service
                                        </option>

                                        @foreach($services as $service)

                                            <option value="{{ $service->id }}">
                                                {{ $service->name }}
                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Variation --}}
                                <div class="col-md-4">

                                    <label for="service_variation_id" class="form-label">
                                        Variation
                                    </label>

                                    <select id="service_variation_id"
                                            class="form-select"
                                            disabled>

                                        <option value="">
                                            Select Variation
                                        </option>

                                    </select>

                                </div>


                                {{-- Quantity --}}
                                <div class="col-md-2">

                                    <label for="service_quantity" class="form-label">
                                        Quantity
                                    </label>

                                    <input type="number"
                                           id="service_quantity"
                                           class="form-control"
                                           value="1"
                                           min="1">

                                </div>


                                {{-- Add Button --}}
                                <div class="col-md-1">

                                    <button type="button"
                                            class="btn btn-primary w-100"
                                            id="addServiceBtn"
                                            title="Add Service">

                                        <i class="bi bi-plus-lg"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Selected Services --}}
                    <div class="table-responsive mt-3">

                        <table class="table table-bordered align-middle mb-0"
                               id="packageServicesTable">

                            <thead class="table-light">

                                <tr>
                                    <th width="40%">Service</th>
                                    <th width="30%">Variation</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>

                            </thead>

                            <tbody id="packageServicesBody">

                                <tr id="noServicesRow">

                                    <td colspan="4"
                                        class="text-center text-muted py-4">

                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>

                                        No services added yet.

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    {{-- Hidden inputs will be generated by JavaScript --}}

                    <div id="packageServiceInputs"></div>

                    @error('package_services')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <hr class="my-4">


                {{-- =====================================================
                    PACKAGE SETTINGS
                ====================================================== --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-gear me-2"></i>
                        Package Settings
                    </h6>

                    <div class="row g-3">

                        {{-- Price --}}
                        <div class="col-md-4">

                            <label for="price" class="form-label">
                                Price
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="price"
                                       id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="5000"
                                       required>

                            </div>

                            @error('price')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Validity --}}
                        <div class="col-md-4">

                            <label for="validity_days" class="form-label">
                                Validity
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="validity_days"
                                       id="validity_days"
                                       class="form-control @error('validity_days') is-invalid @enderror"
                                       value="{{ old('validity_days', 30) }}"
                                       min="1"
                                       required>

                                <span class="input-group-text">
                                    Days
                                </span>

                            </div>

                            @error('validity_days')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Total Uses --}}
                        <div class="col-md-4">

                            <label for="total_uses" class="form-label">
                                Total Uses
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="total_uses"
                                   id="total_uses"
                                   class="form-control @error('total_uses') is-invalid @enderror"
                                   value="{{ old('total_uses', 1) }}"
                                   min="1"
                                   required>

                            @error('total_uses')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Maximum number of times this package can be used.
                            </small>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =====================================================
                    STATUS
                ====================================================== --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-toggle-on me-2"></i>
                        Status
                    </h6>

                    <div class="form-check form-switch">

                        <input type="hidden"
                               name="status"
                               value="0">

                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               name="status"
                               id="status"
                               value="1"
                               {{ old('status', 1) ? 'checked' : '' }}>

                        <label class="form-check-label"
                               for="status">

                            Active Package

                        </label>

                    </div>

                    <small class="text-muted">
                        Active packages will be available for use in the salon.
                    </small>

                </div>


                {{-- =====================================================
                    FORM ACTIONS
                ====================================================== --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.packages.index') }}"
                       class="btn btn-light border">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>
                        Save Package

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>


{{-- =========================================================
    SERVICE DATA + JAVASCRIPT
========================================================= --}}

<script>

    const servicesData = @json($services);

    let packageServices = [];

    const serviceSelect = document.getElementById('service_id');
    const variationSelect = document.getElementById('service_variation_id');
    const quantityInput = document.getElementById('service_quantity');
    const addServiceBtn = document.getElementById('addServiceBtn');
    const servicesBody = document.getElementById('packageServicesBody');
    const noServicesRow = document.getElementById('noServicesRow');
    const serviceInputs = document.getElementById('packageServiceInputs');


    /*
    |--------------------------------------------------------------------------
    | Service Change
    |--------------------------------------------------------------------------
    */

    serviceSelect.addEventListener('change', function () {

        const serviceId = parseInt(this.value);

        variationSelect.innerHTML = `
            <option value="">
                Select Variation
            </option>
        `;

        variationSelect.disabled = true;

        if (!serviceId) {
            return;
        }

        const service = servicesData.find(
            item => parseInt(item.id) === serviceId
        );

        if (!service) {
            return;
        }

        if (
            service.variations &&
            service.variations.length > 0
        ) {

            service.variations.forEach(function (variation) {

                const option = document.createElement('option');

                option.value = variation.id;

                option.textContent = variation.name;

                variationSelect.appendChild(option);

            });

            variationSelect.disabled = false;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Add Service
    |--------------------------------------------------------------------------
    */

    addServiceBtn.addEventListener('click', function () {

        const serviceId = parseInt(serviceSelect.value);

        const variationId = variationSelect.value
            ? parseInt(variationSelect.value)
            : null;

        const quantity = parseInt(quantityInput.value);


        if (!serviceId) {

            alert('Please select a service.');

            return;

        }


        if (!quantity || quantity < 1) {

            alert('Please enter a valid quantity.');

            return;

        }


        const service = servicesData.find(
            item => parseInt(item.id) === serviceId
        );


        if (!service) {

            alert('Selected service not found.');

            return;

        }


        let variation = null;


        if (
            variationId &&
            service.variations
        ) {

            variation = service.variations.find(
                item => parseInt(item.id) === variationId
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate
        |--------------------------------------------------------------------------
        */

        const duplicate = packageServices.some(function (item) {

            return parseInt(item.service_id) === serviceId &&
                   parseInt(item.service_variation_id || 0) ===
                   parseInt(variationId || 0);

        });


        if (duplicate) {

            alert('This service/variation is already added.');

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Add To Array
        |--------------------------------------------------------------------------
        */

        packageServices.push({

            service_id: serviceId,

            service_variation_id: variationId,

            service_name: service.name,

            variation_name: variation
                ? variation.name
                : 'Default',

            quantity: quantity

        });


        renderPackageServices();


        /*
        |--------------------------------------------------------------------------
        | Reset
        |--------------------------------------------------------------------------
        */

        serviceSelect.value = '';

        variationSelect.innerHTML = `
            <option value="">
                Select Variation
            </option>
        `;

        variationSelect.disabled = true;

        quantityInput.value = 1;

    });


    /*
    |--------------------------------------------------------------------------
    | Render Services
    |--------------------------------------------------------------------------
    */

    function renderPackageServices()
    {

        servicesBody.innerHTML = '';

        serviceInputs.innerHTML = '';


        if (packageServices.length === 0) {

            servicesBody.innerHTML = `
                <tr id="noServicesRow">
                    <td colspan="4"
                        class="text-center text-muted py-4">

                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>

                        No services added yet.

                    </td>
                </tr>
            `;

            return;

        }


        packageServices.forEach(function (item, index) {

            servicesBody.innerHTML += `

                <tr>

                    <td>
                        <div class="fw-semibold">
                            ${escapeHtml(item.service_name)}
                        </div>
                    </td>

                    <td>
                        ${escapeHtml(item.variation_name)}
                    </td>

                    <td>
                        ${item.quantity}
                    </td>

                    <td class="text-center">

                        <button type="button"
                                class="btn btn-sm btn-outline-danger"
                                onclick="removePackageService(${index})">

                            <i class="bi bi-trash"></i>

                        </button>

                    </td>

                </tr>

            `;


            /*
            |--------------------------------------------------------------------------
            | Hidden Form Inputs
            |--------------------------------------------------------------------------
            */

            serviceInputs.innerHTML += `

                <input type="hidden"
                       name="package_services[${index}][service_id]"
                       value="${item.service_id}">

                <input type="hidden"
                       name="package_services[${index}][service_variation_id]"
                       value="${item.service_variation_id || ''}">

                <input type="hidden"
                       name="package_services[${index}][quantity]"
                       value="${item.quantity}">

            `;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Remove Service
    |--------------------------------------------------------------------------
    */

    function removePackageService(index)
    {

        packageServices.splice(index, 1);

        renderPackageServices();

    }


    /*
    |--------------------------------------------------------------------------
    | HTML Escape
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {

        if (!value) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /*
    |--------------------------------------------------------------------------
    | Form Validation
    |--------------------------------------------------------------------------
    */

    document.getElementById('packageForm')
        .addEventListener('submit', function (event) {

            if (packageServices.length === 0) {

                event.preventDefault();

                alert('Please add at least one service to the package.');

                return false;

            }

        });

</script>

@endsection

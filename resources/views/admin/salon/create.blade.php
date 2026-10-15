@extends('admin.layout')

@section('title', 'Add Salon')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-shop"></i>
                Add Salon
            </h3>

            <p class="text-muted mb-0">
                Create a new salon profile
            </p>
        </div>

        <a
            href="{{ route('admin.salon.profile') }}"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        action="{{ route('admin.salon.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="card shadow-sm">

            <div class="card-header">

                <h5 class="mb-0">
                    <i class="bi bi-building"></i>
                    Salon Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">


                    {{-- Salon Name --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Salon Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="Enter salon name"
                            required
                        >

                    </div>


                    {{-- Logo --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Salon Logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                        >

                        <small class="text-muted">
                            JPG, JPEG, PNG, WEBP. Max 2MB.
                        </small>

                    </div>


                    {{-- Phone --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone') }}"
                            placeholder="Enter phone number"
                        >

                    </div>


                    {{-- Email --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            placeholder="Enter email"
                        >

                    </div>


                    {{-- Address --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            class="form-control"
                            rows="3"
                            placeholder="Enter salon address"
                        >{{ old('address') }}</textarea>

                    </div>


                    {{-- City --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            value="{{ old('city') }}"
                            placeholder="Enter city"
                        >

                    </div>


                    {{-- State --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            class="form-control"
                            value="{{ old('state') }}"
                            placeholder="Enter state"
                        >

                    </div>


                    {{-- Pincode --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Pincode
                        </label>

                        <input
                            type="text"
                            name="pincode"
                            class="form-control"
                            value="{{ old('pincode') }}"
                            placeholder="Enter pincode"
                        >

                    </div>


                    {{-- GST --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            GST Number
                        </label>

                        <input
                            type="text"
                            name="gst_number"
                            class="form-control"
                            value="{{ old('gst_number') }}"
                            placeholder="Enter GST number"
                        >

                    </div>


                    {{-- Opening Time --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Opening Time
                        </label>

                        <input
                            type="time"
                            name="opening_time"
                            class="form-control"
                            value="{{ old('opening_time') }}"
                        >

                    </div>


                    {{-- Closing Time --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Closing Time
                        </label>

                        <input
                            type="time"
                            name="closing_time"
                            class="form-control"
                            value="{{ old('closing_time') }}"
                        >

                    </div>


                    {{-- Currency --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Currency
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="currency"
                            class="form-select"
                            required
                        >

                            <option value="INR"
                                {{ old('currency', 'INR') == 'INR' ? 'selected' : '' }}>
                                INR - Indian Rupee
                            </option>

                            <option value="USD"
                                {{ old('currency') == 'USD' ? 'selected' : '' }}>
                                USD - US Dollar
                            </option>

                            <option value="EUR"
                                {{ old('currency') == 'EUR' ? 'selected' : '' }}>
                                EUR - Euro
                            </option>

                            <option value="GBP"
                                {{ old('currency') == 'GBP' ? 'selected' : '' }}>
                                GBP - British Pound
                            </option>

                        </select>

                    </div>


                    {{-- Tax --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Tax Percentage
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="tax_percentage"
                            class="form-control"
                            value="{{ old('tax_percentage', 0) }}"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                        >

                    </div>


                    {{-- Invoice Prefix --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Invoice Prefix
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="invoice_prefix"
                            class="form-control"
                            value="{{ old('invoice_prefix', 'INV') }}"
                            placeholder="INV"
                            required
                        >

                    </div>


                    {{-- Appointment Duration --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Default Appointment Duration
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="default_appointment_duration"
                            class="form-select"
                            required
                        >

                            <option value="15"
                                {{ old('default_appointment_duration') == 15 ? 'selected' : '' }}>
                                15 Minutes
                            </option>

                            <option value="30"
                                {{ old('default_appointment_duration', 30) == 30 ? 'selected' : '' }}>
                                30 Minutes
                            </option>

                            <option value="45"
                                {{ old('default_appointment_duration') == 45 ? 'selected' : '' }}>
                                45 Minutes
                            </option>

                            <option value="60"
                                {{ old('default_appointment_duration') == 60 ? 'selected' : '' }}>
                                60 Minutes
                            </option>

                            <option value="90"
                                {{ old('default_appointment_duration') == 90 ? 'selected' : '' }}>
                                90 Minutes
                            </option>

                            <option value="120"
                                {{ old('default_appointment_duration') == 120 ? 'selected' : '' }}>
                                120 Minutes
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label d-block">
                            Status
                        </label>

                        <div class="form-check form-switch mt-2">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                class="form-check-input"
                                id="status"
                                {{ old('status', true) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="status"
                            >
                                Active
                            </label>

                        </div>

                    </div>


                    {{-- Receipt Footer --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Receipt Footer
                        </label>

                        <textarea
                            name="receipt_footer"
                            class="form-control"
                            rows="3"
                            placeholder="Thank you for visiting our salon!"
                        >{{ old('receipt_footer') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- Footer --}}
            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route('admin.salon.profile') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-save"></i>
                    Add Salon
                </button>

            </div>

        </div>

    </form>

</div>

@endsection
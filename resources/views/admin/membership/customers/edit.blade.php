
@extends('admin.layout')

@section('title', 'Edit Customer Membership | Salon Management')

@section('page-title', 'Edit Customer Membership')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Customer Membership
            </h4>

            <p class="text-muted mb-0">
                Update customer membership details.
            </p>
        </div>

        <a href="{{ route('admin.membership.customers.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Edit Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">

                <i class="bi bi-person-vcard me-2"></i>

                Membership Details

            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route(
                'admin.membership.customers.update',
                $customerMembership
            ) }}"
                  method="POST">

                @csrf

                @method('PUT')


                <div class="row g-3">


                    {{-- Customer --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Customer
                            <span class="text-danger">*</span>
                        </label>

                        <select name="customer_id"
                                class="form-select @error('customer_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Customer
                            </option>

                            @foreach($customers as $customer)

                                <option value="{{ $customer->id }}"
                                    {{ old(
                                        'customer_id',
                                        $customerMembership->customer_id
                                    ) == $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }}

                                    @if(!empty($customer->phone))
                                        - {{ $customer->phone }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('customer_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Membership Plan --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Membership Plan
                            <span class="text-danger">*</span>
                        </label>

                        <select name="membership_plan_id"
                                class="form-select @error('membership_plan_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Membership Plan
                            </option>

                            @foreach($membershipPlans as $plan)

                                <option value="{{ $plan->id }}"
                                    {{ old(
                                        'membership_plan_id',
                                        $customerMembership->membership_plan_id
                                    ) == $plan->id ? 'selected' : '' }}>

                                    {{ $plan->name }}

                                    @if(!empty($plan->code))
                                        ({{ $plan->code }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('membership_plan_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Start Date --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Start Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="start_date"
                               value="{{ old(
                                   'start_date',
                                   $customerMembership->start_date
                                       ? $customerMembership->start_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('start_date') is-invalid @enderror"
                               required>

                        @error('start_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Expiry Date --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Expiry Date
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="expiry_date"
                               value="{{ old(
                                   'expiry_date',
                                   $customerMembership->expiry_date
                                       ? $customerMembership->expiry_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('expiry_date') is-invalid @enderror"
                               required>

                        @error('expiry_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Price --}}
                    <div class="col-md-4">

                        <label class="form-label">
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
                                   step="0.01"
                                   min="0"
                                   value="{{ old(
                                       'price',
                                       $customerMembership->price
                                   ) }}"
                                   class="form-control @error('price') is-invalid @enderror"
                                   required>

                        </div>

                        @error('price')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Discount --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Discount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input type="number"
                                   name="discount"
                                   id="discount"
                                   step="0.01"
                                   min="0"
                                   value="{{ old(
                                       'discount',
                                       $customerMembership->discount
                                   ) }}"
                                   class="form-control @error('discount') is-invalid @enderror">

                        </div>

                        @error('discount')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Paid Amount --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Paid Amount
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ₹
                            </span>

                            <input type="number"
                                   name="paid_amount"
                                   id="paid_amount"
                                   step="0.01"
                                   min="0"
                                   value="{{ old(
                                       'paid_amount',
                                       $customerMembership->paid_amount
                                   ) }}"
                                   class="form-control @error('paid_amount') is-invalid @enderror"
                                   required>

                        </div>

                        @error('paid_amount')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="active"
                                {{ old(
                                    'status',
                                    $customerMembership->status
                                ) === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="pending"
                                {{ old(
                                    'status',
                                    $customerMembership->status
                                ) === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="expired"
                                {{ old(
                                    'status',
                                    $customerMembership->status
                                ) === 'expired' ? 'selected' : '' }}>
                                Expired
                            </option>

                            <option value="cancelled"
                                {{ old(
                                    'status',
                                    $customerMembership->status
                                ) === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Renewal Date --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Renewal Date
                        </label>

                        <input type="date"
                               name="renewal_date"
                               value="{{ old(
                                   'renewal_date',
                                   $customerMembership->renewal_date
                                       ? $customerMembership->renewal_date->format('Y-m-d')
                                       : ''
                               ) }}"
                               class="form-control @error('renewal_date') is-invalid @enderror">

                        @error('renewal_date')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Notes --}}
                    <div class="col-12">

                        <label class="form-label">
                            Notes
                        </label>

                        <textarea name="notes"
                                  rows="4"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Enter membership notes...">{{ old(
                                      'notes',
                                      $customerMembership->notes
                                  ) }}</textarea>

                        @error('notes')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route(
                        'admin.membership.customers.index'
                    ) }}"
                       class="btn btn-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>
                        Update Membership

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection


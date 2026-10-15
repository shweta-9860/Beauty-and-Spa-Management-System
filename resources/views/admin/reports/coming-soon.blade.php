@extends('admin.layout')

@section('title', $title . ' | Salon Management')

@section('page-title', $title)

@section('content')

<div class="container-fluid px-0">

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <i class="bi {{ $icon }} display-3 text-primary"></i>

            <h3 class="fw-bold mt-4">
                {{ $title }}
            </h3>

            <p class="text-muted mb-4">
                This report module will be completed according to the
                ZapCodex Salon Management System BRD.
            </p>

            <a href="{{ route('admin.reports.index') }}"
               class="btn btn-primary">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Reports

            </a>

        </div>

    </div>

</div>

@endsection
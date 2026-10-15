@extends('admin.layout')

@section('title', 'Expense Categories | Salon Management')
@section('page-title', 'Expense Categories')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Expense Categories
            </h4>

            <p class="text-muted mb-0">
                Manage categories used for salon expenses.
            </p>
        </div>

        <a href="{{ route('admin.expenses.categories.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Add Category

        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        CATEGORY TABLE
    ========================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">
                    All Expense Categories
                </h5>

                <span class="badge bg-secondary">
                    {{ $categories->total() }} Categories
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($categories->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="70">#</th>

                                <th>Category</th>

                                <th>Description</th>

                                <th width="120">
                                    Status
                                </th>

                                <th width="180"
                                    class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($categories as $category)

                                <tr>

                                    <td>
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>


                                    <td>

                                        <div class="fw-semibold">
                                            {{ $category->name }}
                                        </div>

                                    </td>


                                    <td>

                                        @if($category->description)

                                            <span class="text-muted">
                                                {{ Str::limit($category->description, 80) }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        @if($category->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">

                                            {{-- Edit --}}
                                            <a href="{{ route(
                                                'admin.expenses.categories.edit',
                                                $category
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            {{-- Delete --}}
                                            <form action="{{ route(
                                                'admin.expenses.categories.destroy',
                                                $category
                                            ) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this expense category?'
                                                  );">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Delete">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- =====================================================
                    PAGINATION
                ====================================================== --}}
                @if($categories->hasPages())

                    <div class="card-footer bg-white">

                        {{ $categories->links() }}

                    </div>

                @endif

            @else

                {{-- =====================================================
                    EMPTY STATE
                ====================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-folder2-open fs-1 text-muted"></i>

                    </div>

                    <h5 class="fw-semibold">
                        No Expense Categories Found
                    </h5>

                    <p class="text-muted mb-3">
                        Create your first expense category to get started.
                    </p>

                    <a href="{{ route(
                        'admin.expenses.categories.create'
                    ) }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Add Category

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
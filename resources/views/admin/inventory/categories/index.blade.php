@extends('admin.layout')

@section('title', 'Product Categories')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-tags me-2"></i>Product Categories
            </h3>
            <p class="text-muted mb-0">
                Manage inventory product categories
            </p>
        </div>

        <a href="{{ route('admin.inventory.categories.create') }}"
           class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>
            Add Category
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    {{-- Categories Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                All Categories
            </h5>
        </div>

        <div class="card-body p-0">

            @if($categories->count())

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th width="70">#</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th width="120">Products</th>
                                <th width="120">Status</th>
                                <th width="180" class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($categories as $category)

                                <tr>

                                    <td>
                                        {{ $categories->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $category->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        @if($category->description)
                                            {{ Str::limit($category->description, 60) }}
                                        @else
                                            <span class="text-muted">
                                                —
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-info">
                                            {{ $category->products()->count() }}
                                        </span>
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

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.inventory.categories.edit', $category) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.inventory.categories.destroy', $category) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this category?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @endforeach
                        </tbody>

                    </table>
                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-tags fs-1 text-muted"></i>
                    </div>

                    <h5>No Product Categories Found</h5>

                    <p class="text-muted">
                        Create your first inventory product category.
                    </p>

                    <a href="{{ route('admin.inventory.categories.create') }}"
                       class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Category
                    </a>

                </div>

            @endif

        </div>

        {{-- Pagination --}}
        @if($categories->hasPages())
            <div class="card-footer bg-white">
                {{ $categories->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
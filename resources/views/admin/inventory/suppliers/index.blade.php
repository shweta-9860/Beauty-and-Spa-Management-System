@extends('admin.layout')

@section('title', 'Suppliers')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-truck me-2"></i>
                Suppliers
            </h3>

            <p class="text-muted mb-0">
                Manage inventory suppliers
            </p>
        </div>

        <a href="{{ route('admin.inventory.suppliers.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Add Supplier

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white
                    d-flex justify-content-between
                    align-items-center">

            <h5 class="mb-0">

                <i class="bi bi-list-ul me-2"></i>

                All Suppliers

            </h5>

            <span class="badge bg-secondary">

                {{ $suppliers->total() }} Suppliers

            </span>

        </div>


        <div class="card-body p-0">

            @if($suppliers->count())

                <div class="table-responsive">

                    <table class="table table-hover
                                  align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Supplier</th>

                                <th>Company</th>

                                <th>Phone</th>

                                <th>Email</th>

                                <th>City</th>

                                <th>GST Number</th>

                                <th>Status</th>

                                <th width="130"
                                    class="text-center">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($suppliers as $supplier)

                                <tr>

                                    <td>
                                        {{ $suppliers->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $supplier->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $supplier->company_name ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $supplier->phone ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $supplier->email ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $supplier->city ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $supplier->gst_number ?: '—' }}
                                    </td>

                                    <td>

                                        @if($supplier->status === 'active')

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

                                        <a href="{{ route('admin.inventory.suppliers.edit', $supplier) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <form action="{{ route('admin.inventory.suppliers.destroy', $supplier) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this supplier?');">

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

                    <i class="bi bi-truck fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Suppliers Found
                    </h5>

                    <p class="text-muted">
                        Add your first inventory supplier.
                    </p>

                    <a href="{{ route('admin.inventory.suppliers.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add Supplier

                    </a>

                </div>

            @endif

        </div>


        @if($suppliers->hasPages())

            <div class="card-footer bg-white">

                {{ $suppliers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
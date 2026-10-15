@extends('admin.layout')

@section('title', 'New Purchase')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-cart-plus me-2"></i>
                New Purchase
            </h4>
            <p class="text-muted mb-0">
                Add products received from supplier.
            </p>
        </div>

        <a href="{{ route('admin.inventory.purchases.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>
    </div>
<form action="{{ route('admin.inventory.purchases.store') }}" method="POST" id="purchaseForm">
    @csrf

        {{-- Purchase Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">Purchase Information</h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Supplier --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Supplier <span class="text-danger">*</span>
                        </label>

                        <select name="supplier_id"
                                class="form-select"
                                required>

                            <option value="">Select Supplier</option>

                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                    @if($supplier->company_name)
                                        - {{ $supplier->company_name }}
                                    @endif
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Branch --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Branch <span class="text-danger">*</span>
                        </label>

                        <select name="branch_id"
                                class="form-select"
                                required>

                            <option value="">Select Branch</option>

                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Purchase Date --}}
                    <div class="col-md-4">
                        <label class="form-label">
                            Purchase Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="purchase_date"
                               class="form-control"
                               value="{{ date('Y-m-d') }}"
                               required>
                    </div>

                </div>

            </div>
        </div>


        {{-- Products --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Purchase Items
                </h5>

                <button type="button"
                        class="btn btn-primary btn-sm"
                        onclick="addItem()">

                    <i class="bi bi-plus-lg me-1"></i>
                    Add Product

                </button>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered align-middle"
                           id="itemsTable">

                        <thead class="table-light">

                            <tr>
                                <th style="width: 30%;">
                                    Product
                                </th>

                                <th style="width: 12%;">
                                    Quantity
                                </th>

                                <th style="width: 15%;">
                                    Unit Price
                                </th>

                                <th style="width: 13%;">
                                    Discount
                                </th>

                                <th style="width: 13%;">
                                    Tax
                                </th>

                                <th style="width: 14%;">
                                    Total
                                </th>

                                <th style="width: 3%;"></th>
                            </tr>

                        </thead>

                        <tbody id="itemsBody">

                        </tbody>

                    </table>

                </div>

                <div id="emptyMessage"
                     class="text-center text-muted py-4">

                    <i class="bi bi-box-seam fs-2"></i>

                    <p class="mb-0 mt-2">
                        No products added.
                    </p>

                    <small>
                        Click "Add Product" to add items.
                    </small>

                </div>

            </div>
        </div>


        {{-- Summary --}}
        <div class="row">

            <div class="col-md-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">Notes</h5>
                    </div>

                    <div class="card-body">

                        <textarea name="notes"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Purchase notes..."></textarea>

                    </div>

                </div>

            </div>


            <div class="col-md-5">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">Purchase Summary</h5>
                    </div>

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <span>Subtotal</span>
                            <strong>
                                ₹<span id="subtotal">0.00</span>
                            </strong>
                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Overall Discount
                            </label>

                            <input type="number"
                                   name="discount"
                                   id="discount"
                                   class="form-control"
                                   value="0"
                                   min="0"
                                   step="0.01"
                                   oninput="calculateGrandTotal()">

                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <strong>
                                ₹<span id="taxTotal">0.00</span>
                            </strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">

                            <h5 class="mb-0">
                                Grand Total
                            </h5>

                            <h5 class="mb-0 text-primary">
                                ₹<span id="grandTotal">0.00</span>
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Payment & Status --}}
        <div class="card border-0 shadow-sm mt-4 mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0">Payment & Status</h5>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">
                            Payment Status
                        </label>

                        <select name="payment_status"
                                class="form-select">

                            <option value="pending">
                                Pending
                            </option>

                            <option value="partial">
                                Partial
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Purchase Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="received">
                                Received
                            </option>

                            <option value="draft">
                                Draft
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.inventory.purchases.index') }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>
                Save Purchase

            </button>

        </div>

    </form>

</div>


<script>

let itemIndex = 0;

const products = @json($products);


/**
 * Add Product Row
 */
function addItem()
{
    const tbody = document.getElementById('itemsBody');
    const emptyMessage = document.getElementById('emptyMessage');

    emptyMessage.style.display = 'none';

    let productOptions = `
        <option value="">Select Product</option>
    `;

    products.forEach(product => {

        productOptions += `
            <option value="${product.id}">
                ${product.name}
                ${product.sku ? ' - ' + product.sku : ''}
            </option>
        `;

    });

    const row = document.createElement('tr');

    row.innerHTML = `

        <td>

            <select name="items[${itemIndex}][product_id]"
                    class="form-select product-select"
                    required>

                ${productOptions}

            </select>

        </td>

        <td>

            <input type="number"
                   name="items[${itemIndex}][quantity]"
                   class="form-control quantity"
                   value="1"
                   min="0.01"
                   step="0.01"
                   oninput="calculateRow(this)"
                   required>

        </td>

        <td>

            <input type="number"
                   name="items[${itemIndex}][unit_price]"
                   class="form-control unit-price"
                   value="0"
                   min="0"
                   step="0.01"
                   oninput="calculateRow(this)"
                   required>

        </td>

        <td>

            <input type="number"
                   name="items[${itemIndex}][discount]"
                   class="form-control discount"
                   value="0"
                   min="0"
                   step="0.01"
                   oninput="calculateRow(this)">

        </td>

        <td>

            <input type="number"
                   name="items[${itemIndex}][tax]"
                   class="form-control tax"
                   value="0"
                   min="0"
                   step="0.01"
                   oninput="calculateRow(this)">

        </td>

        <td>

            <input type="text"
                   class="form-control row-total"
                   value="0.00"
                   readonly>

        </td>

        <td class="text-center">

            <button type="button"
                    class="btn btn-sm btn-outline-danger"
                    onclick="removeItem(this)">

                <i class="bi bi-trash"></i>

            </button>

        </td>

    `;

    tbody.appendChild(row);

    itemIndex++;
}


/**
 * Calculate row total
 */
function calculateRow(element)
{
    const row = element.closest('tr');

    const quantity =
        parseFloat(row.querySelector('.quantity').value) || 0;

    const unitPrice =
        parseFloat(row.querySelector('.unit-price').value) || 0;

    const discount =
        parseFloat(row.querySelector('.discount').value) || 0;

    const tax =
        parseFloat(row.querySelector('.tax').value) || 0;

    const baseAmount = quantity * unitPrice;

    const afterDiscount = Math.max(
        baseAmount - discount,
        0
    );

    const taxAmount =
        afterDiscount * tax / 100;

    const total =
        afterDiscount + taxAmount;

    row.querySelector('.row-total').value =
        total.toFixed(2);

    calculateGrandTotal();
}


/**
 * Remove row
 */
function removeItem(button)
{
    button.closest('tr').remove();

    const rows =
        document.querySelectorAll('#itemsBody tr');

    if (rows.length === 0) {
        document.getElementById('emptyMessage').style.display = 'block';
    }

    calculateGrandTotal();
}


/**
 * Calculate purchase summary
 */
function calculateGrandTotal()
{
    let subtotal = 0;
    let taxTotal = 0;

    document.querySelectorAll('#itemsBody tr').forEach(row => {

        const quantity =
            parseFloat(row.querySelector('.quantity').value) || 0;

        const unitPrice =
            parseFloat(row.querySelector('.unit-price').value) || 0;

        const discount =
            parseFloat(row.querySelector('.discount').value) || 0;

        const tax =
            parseFloat(row.querySelector('.tax').value) || 0;

        const baseAmount =
            quantity * unitPrice;

        subtotal += Math.max(
            baseAmount - discount,
            0
        );

        taxTotal +=
            Math.max(baseAmount - discount, 0)
            * tax / 100;

    });

    const discount =
        parseFloat(
            document.getElementById('discount').value
        ) || 0;

    const grandTotal =
        Math.max(
            subtotal - discount + taxTotal,
            0
        );

    document.getElementById('subtotal').innerText =
        subtotal.toFixed(2);

    document.getElementById('taxTotal').innerText =
        taxTotal.toFixed(2);

    document.getElementById('grandTotal').innerText =
        grandTotal.toFixed(2);
}

</script>

@endsection
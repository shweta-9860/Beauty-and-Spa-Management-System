<!DOCTYPE html>

<html>
<head>

<meta charset="UTF-8">

<title>Inventory Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
<style>

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 8px;
        color: #222;
    }

    h2 {
        margin-bottom: 5px;
    }

    .meta {
        color: #666;
        margin-bottom: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #eeeeee;
        font-weight: bold;
    }

    th,
    td {
        border: 1px solid #999;
        padding: 5px;
        vertical-align: top;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .low-stock {
        color: red;
        font-weight: bold;
    }

    .active {
        color: green;
        font-weight: bold;
    }

    .inactive {
        color: #777;
        font-weight: bold;
    }

</style>


</head>

<body>

<h2>Inventory Report</h2>

<div class="meta">
    Total Records: {{ $products->count() }}
    &nbsp; | &nbsp;
    Generated: {{ now()->format('d-m-Y H:i:s') }}
</div>


<table>

    <thead>

        <tr>
            <th>#</th>
            <th>Product</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Purchase Price</th>
            <th>Selling Price</th>
            <th>Stock</th>
            <th>Unit</th>
            <th>Min Stock</th>
            <th>Expiry</th>
            <th>Status</th>
        </tr>

    </thead>

    <tbody>

        @forelse($products as $index => $product)

            <tr>

                <td class="text-center">
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $product->name }}

                    @if($product->brand)
                        <br>
                        <small>{{ $product->brand }}</small>
                    @endif
                </td>

                <td>
                    {{ $product->sku ?: '-' }}
                </td>

                <td>
                    {{ $product->category?->name ?? '-' }}
                </td>

                <td>
                    {{ $product->supplier?->name ?? '-' }}
                </td>

                <td class="text-right">
                    ₹{{ number_format((float) $product->purchase_price, 2) }}
                </td>

                <td class="text-right">
                    ₹{{ number_format((float) $product->selling_price, 2) }}
                </td>

                <td class="text-center">

                    @if((float) $product->stock <= (float) $product->minimum_stock)

                        <span class="low-stock">
                            {{ $product->stock }}
                        </span>

                    @else

                        {{ $product->stock }}

                    @endif

                </td>

                <td class="text-center">
                    {{ $product->unit ?: '-' }}
                </td>

                <td class="text-center">
                    {{ $product->minimum_stock }}
                </td>

                <td>
                    {{ $product->expiry_date?->format('d-m-Y') ?? '-' }}
                </td>

                <td class="text-center">

                    @if($product->status)

                        <span class="active">
                            Active
                        </span>

                    @else

                        <span class="inactive">
                            Inactive
                        </span>

                    @endif

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="12"
                    class="text-center">

                    No inventory products found.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>

</body>
</html>

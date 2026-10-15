<!DOCTYPE html>

<html>
<head>

<meta charset="UTF-8">

<title>Sales Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
<style>

    @page {
        margin: 15px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
        color: #222;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
    }

    .header h1 {
        margin: 0;
        font-size: 18px;
    }

    .header p {
        margin-top: 5px;
        color: #666;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #8b5e6d;
        color: white;
        padding: 6px 4px;
        border: 1px solid #777;
    }

    td {
        padding: 5px 4px;
        border: 1px solid #ccc;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .footer {
        margin-top: 12px;
        text-align: right;
        font-size: 8px;
        color: #777;
    }

</style>


</head>

<body>


<div class="header">

    <h1>Sales Report</h1>

    <p>
        Generated on {{ now()->format('d M Y, h:i A') }}
    </p>

</div>


<table>

    <thead>

        <tr>

            <th>#</th>

            <th>Date</th>

            <th>Invoice</th>

            <th>Customer</th>

            <th>Branch</th>

            <th>Service</th>

            <th>Discount</th>

            <th>Tax</th>

            <th>Product</th>

            <th>Grand Total</th>

            <th>Payment</th>

            <th>Status</th>

        </tr>

    </thead>


    <tbody>

        @forelse($sales as $sale)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $sale->bill_date
                        ? $sale->bill_date->format('d-m-Y')
                        : '-' }}
                </td>

                <td>
                    {{ $sale->invoice_number ?? '-' }}
                </td>

                <td>
                    {{ $sale->customer->name ?? 'Walk-in Customer' }}
                </td>

                <td>
                    {{ $sale->branch->name ?? '-' }}
                </td>

                <td class="right">
                    ₹{{ number_format((float) $sale->service_total, 2) }}
                </td>

                <td class="right">
                    ₹{{ number_format((float) $sale->discount, 2) }}
                </td>

                <td class="right">
                    ₹{{ number_format((float) $sale->tax_amount, 2) }}
                </td>

                <td class="right">
                    ₹{{ number_format((float) $sale->product_amount, 2) }}
                </td>

                <td class="right">
                    <strong>
                        ₹{{ number_format((float) $sale->grand_total, 2) }}
                    </strong>
                </td>

                <td>
                    {{ ucfirst(str_replace('_', ' ', $sale->payment_method ?? '-')) }}
                </td>

                <td>
                    {{ ucfirst($sale->payment_status ?? '-') }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="12" class="center">
                    No sales found.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>


<div class="footer">

    Total Records: {{ $sales->count() }}

    &nbsp; | &nbsp;

    Salon Management System

</div>


</body>
</html>

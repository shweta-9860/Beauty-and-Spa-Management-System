<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">


<title>Customer Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
<style>
    @page {
        margin: 20px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #222;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        margin: 0;
        font-size: 20px;
    }

    .header p {
        margin-top: 5px;
        color: #666;
    }

    .summary {
        margin-bottom: 15px;
        padding: 8px;
        background: #f5f5f5;
        border: 1px solid #ddd;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #8b5e6d;
        color: white;
        padding: 7px 5px;
        border: 1px solid #777;
        text-align: left;
    }

    td {
        padding: 6px 5px;
        border: 1px solid #ccc;
    }

    .center {
        text-align: center;
    }

    .footer {
        margin-top: 15px;
        text-align: right;
        font-size: 9px;
        color: #777;
    }
</style>


</head>

<body>

<div class="header">
    <h1>Customer Report</h1>

    <p>
        Generated on {{ now()->format('d M Y, h:i A') }}
    </p>
</div>

<div class="summary">
    <strong>Total Customers:</strong>
    {{ $customers->count() }}
</div>

<table>
    <thead>
        <tr>
            <th width="5%">#</th>
            <th width="18%">Name</th>
            <th width="14%">Phone</th>
            <th width="20%">Email</th>
            <th width="10%">Gender</th>
            <th width="15%">Branch</th>
            <th width="8%">Status</th>
            <th width="10%">Registered</th>
        </tr>
    </thead>

    <tbody>

        @forelse($customers as $customer)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $customer->name ?? '-' }}
                </td>

                <td>
                    {{ $customer->phone ?? '-' }}
                </td>

                <td>
                    {{ $customer->email ?? '-' }}
                </td>

                <td>
                    {{ $customer->gender ?? '-' }}
                </td>

                <td>
                    {{ $customer->branch->name ?? '-' }}
                </td>

                <td>
                    {{ $customer->status ? 'Active' : 'Inactive' }}
                </td>

                <td>
                    {{ $customer->created_at
                        ? $customer->created_at->format('d-m-Y')
                        : '-' }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="8" class="center">
                    No customers found.
                </td>
            </tr>

        @endforelse

    </tbody>
</table>

<div class="footer">
    Salon Management System
</div>


</body>
</html>

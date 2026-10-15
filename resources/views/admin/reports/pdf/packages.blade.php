<!DOCTYPE html>

<html>
<head>

<meta charset="UTF-8">

<title>Package Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
<style>

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #222;
    }

    h2 {
        margin-bottom: 5px;
    }

    .meta {
        color: #666;
        margin-bottom: 15px;
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
        padding: 6px;
        vertical-align: top;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
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

<h2>Package Report</h2>

<div class="meta">
    Total Records: {{ $packages->count() }}
    &nbsp; | &nbsp;
    Generated: {{ now()->format('d-m-Y H:i:s') }}
</div>


<table>

    <thead>

        <tr>
            <th>#</th>
            <th>Package Name</th>
            <th>Code</th>
            <th>Description</th>
            <th>Price</th>
            <th>Validity</th>
            <th>Total Uses</th>
            <th>Status</th>
            <th>Created</th>
        </tr>

    </thead>

    <tbody>

        @forelse($packages as $index => $package)

            <tr>

                <td class="text-center">
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $package->name }}
                </td>

                <td>
                    {{ $package->code ?: '-' }}
                </td>

                <td>
                    {{ $package->description ?: '-' }}
                </td>

                <td class="text-right">
                    ₹{{ number_format((float) $package->price, 2) }}
                </td>

                <td class="text-center">
                    {{ $package->validity_days }} days
                </td>

                <td class="text-center">
                    {{ $package->total_uses }}
                </td>

                <td class="text-center">

                    @if($package->status)

                        <span class="active">
                            Active
                        </span>

                    @else

                        <span class="inactive">
                            Inactive
                        </span>

                    @endif

                </td>

                <td>
                    {{ $package->created_at?->format('d-m-Y') ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="9"
                    class="text-center">

                    No packages found.

                </td>

            </tr>

        @endforelse

    </tbody>

</table>


</body>
</html>

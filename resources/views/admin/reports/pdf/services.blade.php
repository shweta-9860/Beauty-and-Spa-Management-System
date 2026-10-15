<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Service Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
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

        th,
        td {
            border: 1px solid #999;
            padding: 7px;
            vertical-align: top;
        }

        th {
            background: #eeeeee;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

    </style>

</head>

<body>

    <h2>Service Report</h2>

    <div class="meta">
        Total Records: {{ $services->count() }}
        <br>
        Generated: {{ now()->format('d-m-Y H:i') }}
    </div>

    <table>

        <thead>

            <tr>
                <th width="40">#</th>
                <th>Service</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created</th>
            </tr>

        </thead>

        <tbody>

            @forelse($services as $index => $service)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $service->name }}
                    </td>

                    <td>
                        {{ $service->description ?? '-' }}
                    </td>

                    <td>
                        {{ ucfirst($service->status ?? 'Inactive') }}
                    </td>

                    <td>
                        {{ $service->created_at
                            ? $service->created_at->format('d-m-Y')
                            : '-' }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No services found.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</body>
</html>
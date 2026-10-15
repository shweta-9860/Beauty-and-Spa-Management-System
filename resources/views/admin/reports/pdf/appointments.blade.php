<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">

<title>Appointment Report</title>
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
        margin: 5px 0 0;
        color: #666;
    }

    .summary {
        margin-bottom: 15px;
        padding: 8px;
        border: 1px solid #ddd;
        background: #f7f7f7;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #8b5e6d;
        color: white;
        font-weight: bold;
        padding: 7px 5px;
        border: 1px solid #777;
        text-align: left;
    }

    td {
        padding: 6px 5px;
        border: 1px solid #ccc;
        vertical-align: top;
    }

    .text-center {
        text-align: center;
    }

    .status {
        text-transform: capitalize;
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
    <h1>Appointment Report</h1>
    <p>
        Generated on {{ now()->format('d M Y, h:i A') }}
    </p>
</div>

<div class="summary">
    <strong>Total Appointments:</strong>
    {{ $appointments->count() }}
</div>

<table>
    <thead>
        <tr>
            <th width="4%">#</th>
            <th width="10%">Date</th>
            <th width="10%">Time</th>
            <th width="15%">Customer</th>
            <th width="10%">Phone</th>
            <th width="15%">Service</th>
            <th width="13%">Staff</th>
            <th width="13%">Branch</th>
            <th width="10%">Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($appointments as $appointment)

            <tr>
                <td class="text-center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $appointment->appointment_date
                        ? $appointment->appointment_date->format('d-m-Y')
                        : '-' }}
                </td>

                <td>
                    {{ $appointment->start_time
                        ? \Carbon\Carbon::parse($appointment->start_time)->format('h:i A')
                        : '-' }}

                    @if($appointment->end_time)
                        -
                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                    @endif
                </td>

                <td>
                    {{ $appointment->customer->name ?? '-' }}
                </td>

                <td>
                    {{ $appointment->customer->phone ?? '-' }}
                </td>

                <td>
                    {{ $appointment->service->name ?? '-' }}
                </td>

                <td>
                    {{ $appointment->staff->name ?? '-' }}
                </td>

                <td>
                    {{ $appointment->branch->name ?? '-' }}
                </td>

                <td class="status">
                    {{ str_replace('_', ' ', $appointment->status ?? '-') }}
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center">
                    No appointments found.
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

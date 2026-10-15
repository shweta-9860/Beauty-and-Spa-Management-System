<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Staff Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
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
            padding: 5px;
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

    <h2>Staff Report</h2>

    <div class="meta">

        Total Records: {{ $staff->count() }}

        <br>

        Generated:
        {{ now()->format('d-m-Y H:i') }}

    </div>


    <table>

        <thead>

            <tr>

                <th>#</th>
                <th>Staff</th>
                <th>Role</th>
                <th>Specialization</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Commission</th>
                <th>Joining Date</th>
                <th>Branch</th>
                <th>Status</th>

            </tr>

        </thead>


        <tbody>

            @forelse($staff as $index => $member)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $member->name }}
                    </td>

                    <td>
                        {{ $member->role
                            ? ucwords(str_replace('_', ' ', $member->role))
                            : '-' }}
                    </td>

                    <td>
                        {{ $member->specialization ?? '-' }}
                    </td>

                    <td>
                        {{ $member->phone ?? '-' }}
                    </td>

                    <td>
                        {{ $member->email ?? '-' }}
                    </td>

                    <td>

                        @if($member->commission_type)

                            {{ ucwords(str_replace('_', ' ', $member->commission_type)) }}

                            @if($member->commission_value !== null)
                                : {{ $member->commission_value }}
                            @endif

                        @else

                            -

                        @endif

                    </td>

                    <td>

                        {{ $member->joining_date
                            ? \Carbon\Carbon::parse($member->joining_date)->format('d-m-Y')
                            : '-' }}

                    </td>

                    <td>
                        {{ $member->branch->name ?? '-' }}
                    </td>

                    <td>
                        {{ ucfirst($member->status ?? 'Inactive') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="10"
                        class="text-center"
                    >
                        No staff found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>
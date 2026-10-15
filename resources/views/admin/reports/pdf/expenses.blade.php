<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Expense Report</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 4px 0;
            color: #666;
        }

        .summary {
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .summary-title {
            font-size: 9px;
            color: #666;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            vertical-align: middle;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background: #f8f8f8;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #777;
        }

    </style>
</head>

<body>

    {{-- Header --}}

    <div class="header">

        <h2>
            Expense Report
        </h2>

        <p>
            Generated on:
            {{ now()->format('d-m-Y h:i A') }}
        </p>

    </div>


    {{-- Summary --}}

    <table class="summary">

        <tr>

            <td width="50%" class="summary-box">

                <div class="summary-title">
                    Total Expense
                </div>

                <div class="summary-value">

                    ₹{{ number_format(
                        (float) $totalExpenses,
                        2
                    ) }}

                </div>

            </td>


            <td width="50%" class="summary-box">

                <div class="summary-title">
                    Total Records
                </div>

                <div class="summary-value">

                    {{ $expenses->count() }}

                </div>

            </td>

        </tr>

    </table>


    {{-- Expense Table --}}

    <table>

        <thead>

            <tr>

                <th width="5%">
                    #
                </th>

                <th>
                    Date
                </th>

                <th>
                    Title
                </th>

                <th>
                    Category
                </th>

                <th>
                    Branch
                </th>

                <th>
                    Paid To
                </th>

                <th>
                    Payment
                </th>

                <th>
                    Reference
                </th>

                <th>
                    Amount
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($expenses as $expense)

                <tr>

                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>


                    <td>

                        {{ $expense->expense_date
                            ? $expense->expense_date->format('d-m-Y')
                            : '-' }}

                    </td>


                    <td>

                        {{ $expense->title }}

                    </td>


                    <td>

                        {{ $expense->category->name ?? '-' }}

                    </td>


                    <td>

                        {{ $expense->branch->name ?? 'All Branches' }}

                    </td>


                    <td>

                        {{ $expense->paid_to ?? '-' }}

                    </td>


                    <td>

                        {{ ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $expense->payment_method ?? '-'
                            )
                        ) }}

                    </td>


                    <td>

                        {{ $expense->reference_no ?? '-' }}

                    </td>


                    <td class="text-right">

                        ₹{{ number_format(
                            (float) $expense->amount,
                            2
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="9"
                        class="text-center">

                        No expenses found.

                    </td>

                </tr>

            @endforelse


            {{-- Total --}}

            <tr class="total-row">

                <td colspan="8" class="text-right">

                    Total Expense

                </td>

                <td class="text-right">

                    ₹{{ number_format(
                        (float) $totalExpenses,
                        2
                    ) }}

                </td>

            </tr>

        </tbody>

    </table>


    {{-- Footer --}}

    <div class="footer">

        Expense Report | Salon Management System

    </div>

</body>
</html>
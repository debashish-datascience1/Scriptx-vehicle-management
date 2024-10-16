<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Book Report - {{ date('Y-m-d') }}</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary div {
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 30px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cash Book Report</h1>
        <div class="summary">
            <div><strong>Date:</strong> {{ date(Hyvikk::get('date_format'), strtotime($date)) }}</div>
        </div>

        <div class="section">
            <h2>Income Summary</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Booking Income</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($total_income - $tyre_sales, 2) }}</td>
                </tr>
                <tr>
                    <td>Tyre Sales</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($tyre_sales, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Income</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($total_income, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Expense Summary</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Fuel Costs</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($fuel_costs, 2) }}</td>
                </tr>
                <tr>
                    <td>Driver Advances</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($other_costs, 2) }}</td>
                </tr>
                <tr>
                    <td>Legal Costs</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($legal_costs, 2) }}</td>
                </tr>
                <tr>
                    <td>Tyre Purchase</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($tyre_purchase, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Expenses</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($total_expenses, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="summary">
            <div><strong>Cash Balance:</strong> {{ Hyvikk::get('currency') }} {{ number_format($cash_balance, 2) }}</div>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Cash Book Report</h1>
        <div class="summary">
            <div><strong>Date:</strong> {{ date(Hyvikk::get('date_format'), strtotime($date)) }}</div>
            <div><strong>Total Income:</strong> {{ Hyvikk::get('currency') }} {{ number_format($total_income, 2) }}</div>
            <div><strong>Total Expenses:</strong> {{ Hyvikk::get('currency') }} {{ number_format($total_expenses, 2) }}</div>
            <div><strong>Cash Balance:</strong> {{ Hyvikk::get('currency') }} {{ number_format($cash_balance, 2) }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Pickup Time</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                    <td>{{ $booking->vehicle->makeModel ?? 'N/A' }}</td>
                    <td>{{ date(Hyvikk::get('date_format'), strtotime($booking->pickup)) }}</td>
                    <td>{{ Hyvikk::get('currency') }} {{ number_format($booking->getMeta('total_price'), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
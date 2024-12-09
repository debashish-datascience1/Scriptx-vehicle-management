<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Hyvikk::get('app_name') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cdn/bootstrap.min.css') }}" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/cdn/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link href="{{ asset('assets/css/cdn/ionicons.min.css') }}" rel="stylesheet">
    <!-- Theme style -->
    <link href="{{ asset('assets/css/AdminLTE.min.css') }}" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="{{ asset('assets/css/cdn/fonts.css') }}">
    <style type="text/css">
        body {
            height: auto;
        }
    </style>
</head>

<body onload="window.print();">
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <span class="logo-lg">
                            <img src="{{ asset('assets/images/' . Hyvikk::get('icon_img')) }}" class="navbar-brand"
                                style="margin-top: -15px">
                            {{ Hyvikk::get('app_name') }}
                        </span>
                        <small class="pull-right"> <b>@lang('fleet.date') : </b>
                            <strong>{{ Helper::getCanonicalDateTime(date('Y-m-d H:i:s'), 'default') }} /
                                {{ Helper::getCanonicalDateTime(date('Y-m-d H:i:s')) }}</strong></small>
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <h3>Customer Payment Report</h3>
                    <h4><strong>{{ $customer_data->name }}</strong></h4>
                    <small><strong>{{ Helper::getCanonicalDate($from_date, 'default') }} -
                            {{ Helper::getCanonicalDate($to_date, 'default') }}</strong></small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span style="float: right;font-weight:700"> Opening Balance : {{ Hyvikk::get('currency') }}
                        {{ bcdiv($opening_balance, 1, 2) }}</span>
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-inverse">
                            <tr>
                                <th>SL#</th>
                                <th>Date</th>
                                <th>Ref. No.</th>
                                <th>Particulars</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $k => $row)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td nowrap>{{ Helper::getCanonicalDate($row->date, 'default') }}</td>
                                    <td>{{ $row->transaction_id }}</td>
                                    <td>
                                        @if ($row->param_id == 18)
                                            @if ($row->is_bulk != 1 && $row->booking)
                                                Freight of
                                                {{ Hyvikk::get('currency') }}{{ $row->booking->total_price ?? '-' }}
                                                containing {{ $row->booking->material ?? '-' }}
                                                ({{ $row->booking->loadqty ?? '-' }}
                                                {{ $row->booking->loadtype ? Helper::getParamFromID($row->booking->loadtype)->label : '-' }})
                                                having price ({{ $row->booking->loadprice ?? '-' }}) transported by
                                                <strong>{{ optional($row->booking->vehicle)->license_plate ?? '-' }}</strong>
                                                ({{ optional($row->booking->driver)->name ?? '-' }})
                                                on
                                                {{ Helper::getCanonicalDateTime($row->booking->pickup ?? null, 'default') }}
                                                for {{ $row->booking->distance ?? '-' }}
                                                from {{ $row->booking->pickup_addr ?? '-' }}
                                                to {{ $row->booking->dest_addr ?? '-' }}
                                                in {{ $row->booking->duration_map ?? '-' }}
                                            @endif

                                            @if ($row->is_bulk == 1)
                                                Bulk Paid towards Booking
                                            @endif
                                        @else
                                            Unexpected transaction type
                                        @endif
                                    </td>
                                    <td>
                                        @if ($row->is_bulk != 1)
                                            {{ bcdiv($row->total, 1, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($row->customer_payment !== null)
                                            {{ bcdiv($row->customer_payment, 1, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ bcdiv($row->total, 1, 2) - bcdiv($row->customer_payment, 1, 2) }}</td>
                                    <td>{{ $row->remarks ?? '-' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="3"></th>
                                <th>A/C TOTAL</th>
                                <th nowrap>{{ Hyvikk::get('currency') }}
                                    {{ bcdiv($transactions->where('is_bulk', null)->sum('total'), 1, 2) }}</th>
                                <th nowrap>{{ Hyvikk::get('currency') }}
                                    {{ bcdiv(
                                        $transactions->filter(function ($transaction) {
                                                return $transaction->is_bulk !== 1 && $transaction->customer_payment !== null;
                                            })->sum('customer_payment'),
                                        1,
                                        2,
                                    ) }}
                                </th>

                                <th nowrap>{{ Hyvikk::get('currency') }}
                                    {{ bcdiv(
                                        $transactions->sum(function ($row) {
                                            return bcdiv($row->total, 1, 2) - bcdiv($row->customer_payment ?? 0, 1, 2);
                                        }),
                                        1,
                                        2,
                                    ) }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <!-- ./wrapper -->
</body>

</html>

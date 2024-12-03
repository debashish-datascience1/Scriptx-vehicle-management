@extends('layouts.app')
@php($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Reports</a></li>
    <li class="breadcrumb-item active">Fastag Report</li>
@endsection

@section('extra_css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">

    <style type="text/css">
        .form-label {
            display: block !important;
        }

        .mybtn1 {
            padding: 4px 8px;
        }

        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }

        .fullsize {
            width: 100% !important;
        }

        .newrow {
            margin: 0 auto;
            width: 100%;
            margin-bottom: 15px;
        }

        .dateShow {
            padding-right: 13px
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Fastag Report</h3>
                </div>

                <div class="card-body">
                    {!! Form::open(['route' => 'reports.fastag', 'method' => 'post', 'class' => 'form-block']) !!}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('bank_account_id', 'Fastag Account', ['class' => 'form-label']) !!}
                                {!! Form::select('bank_account_id', 
                                    $bank_accounts, 
                                    $request['bank_account_id'] ?? null, [
                                    'class' => 'form-control',
                                    'id' => 'bank_account_id',
                                    'placeholder' => 'Select Fastag Account',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
                                {!! Form::text(
                                    'from_date',
                                    isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,
                                    ['class' => 'form-control', 'readonly'],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
                                {!! Form::text('to_date', isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null, [
                                    'class' => 'form-control',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info gen_report">@lang('fleet.generate_report')</button>
                            <button type="submit" formaction="{{ route('print.fastag.report') }}" formtarget="_blank"
                                class="btn btn-danger print_report">
                                <i class="fa fa-print"></i> @lang('fleet.print')
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @if (isset($transactions))
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Fastag Report</h3>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Toll Gate Name</th>
                                        <th>Amount</th>
                                        <th>Fastag</th>
                                        <th>Date</th>
                                        <th>Vehicle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $k => $row)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $row['toll_gate_name'] ?? '-' }}</td>
                                            <td>{{ bcdiv($row['amount'], 1, 2) }}</td>
                                            <td>{{ $row['fastag'] ?? '-' }}</td>
                                            <td>{{ Helper::getCanonicalDate($row['date'], 'default') }}</td>
                                            <td>
                                                @if (isset($row['vehicle_id']))
                                                    {{ optional(\App\Model\VehicleModel::find($row['vehicle_id']))->license_plate ?? '-' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <table class="table">
                                <tr>
                                    <th style="float:right">Total Amount : {{ Hyvikk::get('currency') }}
                                        {{ bcdiv($total_amount, 1, 2) }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('assets/js/cdn/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/cdn/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/cdn/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" />');
            });

            var myTable = $('#myTable').DataTable({
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                    ]
                }],
                "language": {
                    "url": '{{ __('fleet.datatable_lang') }}',
                },
                "initComplete": function() {
                    myTable.columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change', function() {
                            that.search(this.value).draw();
                        });
                    });
                }
            });

            // Dates
            $('#from_date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            $('#to_date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

           $(".gen_report, .print_report").on("click", function() {
                var blankTest = /\S/
                var bank_account_id = $("#bank_account_id").val();
                if (!blankTest.test(bank_account_id)) {
                    alert("Please choose a Fastag account");
                    $("#bank_account_id").focus();
                    return false;
                }
            });
        });
    </script>
@endsection

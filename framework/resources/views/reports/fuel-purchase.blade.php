@extends('layouts.app')
@php($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Reports</a></li>
    <li class="breadcrumb-item active">Fuel Purchase Report</li>
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
                    <h3 class="card-title">Fuel Purchase Report</h3>
                </div>

                <div class="card-body">
                    {!! Form::open(['route' => 'reports.fuel-purchase-post', 'method' => 'post', 'class' => 'form-block']) !!}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('vendor_id', __('fleet.vendor'), ['class' => 'form-label']) !!}
                                {!! Form::select('vendor_id', 
                                    $vendors, 
                                    $request['vendor_id'] ?? null, [
                                    'class' => 'form-control',
                                    'id' => 'vendor_id',
                                    'placeholder' => 'Select Vendor',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']) !!}
                                {!! Form::select('fuel_type', 
                                    $fuel_types, 
                                    $request['fuel_type'] ?? null, [
                                    'class' => 'form-control vehicles fullsize',
                                    'id' => 'fuel_type',
                                    'placeholder' => 'Select Fuel Type',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']) !!}
                                {!! Form::text('from_date', 
                                    isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,
                                    ['class' => 'form-control', 'readonly']
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']) !!}
                                {!! Form::text('to_date', 
                                    isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null,
                                    ['class' => 'form-control', 'readonly']
                                ) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info gen_report">@lang('fleet.generate_report')</button>
                            <button type="submit" formaction="{{ route('print.fuel-purchase.report') }}" formtarget="_blank"
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
                            <h3 class="card-title">Fuel Purchase Report</h3>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>@lang('fleet.vendor')</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $k => $row)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ optional($row->vendor)->name ?? '-' }}</td>
                                            <td>{{ $row->quantity }}</td>
                                            <td>{{ bcdiv($row->amount, 1, 2) }}</td>
                                            <td>{{ $row->remarks ?? '-' }}</td>
                                            <td>{{ Helper::getCanonicalDate($row->date, 'default') }}</td>
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
                var vendor_id = $("#vendor_id").val();
                if (!blankTest.test(vendor_id)) {
                    alert("Please choose a vendor");
                    $("#vendor_id").focus();
                    return false;
                }
            });
        });
    </script>
@endsection
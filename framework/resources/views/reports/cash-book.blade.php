@extends('layouts.app')
@php
$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y'
@endphp

@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Cash Book</li>
@endsection

@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
    .form-label {
        display: block !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Cash Book Report</h3>
            </div>

            <div class="card-body">
                {!! Form::open(['route' => 'reports.cash-book', 'method' => 'post', 'class' => 'form-inline']) !!}
                <div class="row w-100">
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></span></div>
                                {!! Form::text('date', $date, ['class' => 'form-control', 'required', 'id' => 'date']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
                        <button type="submit" formaction="{{ route('reports.cash-book.print') }}" class="btn btn-danger" formtarget="_blank"><i class="fa fa-print"></i> @lang('fleet.print')</button>                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@if(isset($bookings))
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Cash Book Summary for {{ date($date_format_setting, strtotime($date)) }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Total Income:</strong> {{ Hyvikk::get('currency') }} {{ number_format($total_income, 2) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Total Expenses:</strong> {{ Hyvikk::get('currency') }} {{ number_format($total_expenses, 2) }}
                    </div>
                    <div class="col-md-4">
                        <strong>Cash Balance:</strong> {{ Hyvikk::get('currency') }} {{ number_format($cash_balance, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Booking Details</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover" id="myTable">
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
                            <td>{{ date($date_format_setting, strtotime($booking->pickup)) }}</td>
                            <td>{{ Hyvikk::get('currency') }} {{ number_format($booking->getMeta('total_price'), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section("script")
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    $('#myTable').DataTable({
        "language": {
            "url": '{{ __("fleet.datatable_lang") }}',
        },
        "ordering": false
    });
});
</script>
@endsection
@extends('layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('loan-give.index') }}">@lang('fleet.loan_take')</a></li>
<li class="breadcrumb-item active">@lang('fleet.loan_details')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.loan_details')</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>@lang('fleet.from'):</strong> {{ $loanTake->from }}</p>
            <p><strong>@lang('fleet.date'):</strong> {{ $loanTake->date }}</p>
            <p><strong>@lang('fleet.amount'):</strong> {{ number_format($loanTake->amount, 2) }}</p>
            <p><strong>@lang('fleet.remaining_amount'):</strong> {{ number_format($loanTake->remaining_amount, 2) }}</p>
          </div>
        </div>
        <hr>
        <h4>@lang('fleet.collect_history')</h4>
        <table class="table">
          <thead>
            <tr>
              <th>@lang('fleet.date')</th>
              <th>@lang('fleet.amount')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($loanTake->returns as $return)
            <tr>
              <td>{{ $return->date }}</td>
              <td>{{ number_format($return->amount, 2) }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
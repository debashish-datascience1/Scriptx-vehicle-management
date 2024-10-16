@extends('layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('loan-take.index') }}">@lang('fleet.loan_take')</a></li>
<li class="breadcrumb-item active">@lang('fleet.return_loan')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.return_loan')</h3>
      </div>

      <div class="card-body">
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {!! Form::open(['route' => ['loan-take.process-return', $loanTake->id], 'method' => 'POST']) !!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('from', __('fleet.from'), ['class' => 'form-label']) !!}
              {!! Form::text('from', $loanTake->from, ['class' => 'form-control', 'readonly']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('date', __('fleet.return_date'), ['class' => 'form-label']) !!}
              {!! Form::date('date', null, ['class' => 'form-control', 'required']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('amount', __('fleet.return_amount'), ['class' => 'form-label']) !!}
              {!! Form::number('amount', null, ['class' => 'form-control', 'required', 'step' => 'any']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('remaining_amount', __('fleet.remaining_amount'), ['class' => 'form-label']) !!}
              {!! Form::number('remaining_amount', $loanTake->remaining_amount, ['class' => 'form-control', 'readonly']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']) !!}
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection
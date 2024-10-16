@extends('layouts.app')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('loan-give.index') }}">@lang('fleet.loan_take')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_loan_take')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_loan_take')</h3>
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

        {!! Form::model($loanTake, ['route' => ['loan-give.update', $loanTake->id], 'method'=>'PATCH']) !!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('date', __('fleet.date'), ['class' => 'form-label']) !!}
              {!! Form::date('date', null, ['class' => 'form-control', 'required']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('from', __('fleet.to'), ['class' => 'form-label']) !!}
              {!! Form::text('from', null, ['class' => 'form-control', 'required']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label']) !!}
              {!! Form::number('amount', null, ['class' => 'form-control', 'required', 'step' => 'any', 'min' => '0']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection
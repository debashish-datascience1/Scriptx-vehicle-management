@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">@lang('menu.settings')</li>
<li class="breadcrumb-item active">@lang('fleet.payment_settings')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.payment_settings')
        </h3>
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

        {!! Form::open(['url' => 'admin/payment-settings','method'=>'post']) !!}
        <div class="row">
          <div class="col-md-6">
            @php
            $methods=json_decode(Hyvikk::payment('method'));
            @endphp
            <div class="form-group">
              {!! Form::label('payment_method', __('fleet.payment_method') , ['class' => 'form-label']) !!}<br>
              <input type="checkbox" name="method[]" class="method" value="cash" id="cash" @if(in_array("cash", $methods)) checked @endif> @lang('fleet.cash') &nbsp; &nbsp;
              <input type="checkbox" name="method[]" class="method" value="stripe" id="stripe" @if(in_array("stripe", $methods)) checked @endif> @lang('fleet.stripe') &nbsp; &nbsp;
              <input type="checkbox" name="method[]" class="method" value="razorpay" id="razorpay" @if(in_array("razorpay", $methods)) checked @endif> @lang('fleet.razorpay')
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('currency_code', __('fleet.currency_code'), ['class' => 'form-label required']) !!}
              <br>
              {!! Form::select('currency_code',config('currency'),Hyvikk::payment('currency_code'),['class' => 'form-control','required','id'=>'currency_code','style'=>'width:100%']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('stripe_publishable_key',__('fleet.stripe_publishable_key'),['class'=>"form-label"]) !!}
              {!! Form::text('stripe_publishable_key',
              Hyvikk::payment('stripe_publishable_key'),['class'=>"form-control stripe",'readonly']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('stripe_secret_key',__('fleet.stripe_secret_key'),['class'=>"form-label"]) !!}
              {!! Form::text('stripe_secret_key',
              Hyvikk::payment('stripe_secret_key'),['class'=>"form-control stripe",'readonly']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('razorpay_key',__('fleet.razorpay_key'),['class'=>"form-label"]) !!}
              {!! Form::text('razorpay_key',
              Hyvikk::payment('razorpay_key'),['class'=>"form-control razorpay",'readonly']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('razorpay_secret',__('fleet.razorpay_secret'),['class'=>"form-label"]) !!}
              {!! Form::text('razorpay_secret',
              Hyvikk::payment('razorpay_secret'),['class'=>"form-control razorpay",'readonly']) !!}
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <h6 class="text-danger"> <strong>@lang('fleet.important_Notes'):</strong></h6>
              <ol class="text-muted">
                <li>To enable or disable international card payments from your <strong>RazorPay</strong> Dashboard: <a href="https://razorpay.com/docs/international-payments/#enable-or-disable-international-payments-from-the-dashboard" target="_blank">Click Here</a>
                <br>
                If you do not want to accept payments in currencies apart from INR (₹), you can turn off <strong>International Card Payment</strong> using the toggle switch <a href="https://dashboard.razorpay.com/#/app/config" target="_blank">available here.</a></li>
                <li>you can automatically email your customers upon successful payments using <strong>Stripe</strong>. Enable this feature with the email customers for successful payments option in your email receipt settings. <a href="https://dashboard.stripe.com/account/emails" target="_blank">Click Here</a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="col-md-2">
          <div class="form-group">
            <input type="submit"  class="form-control btn btn-success"  value="@lang('fleet.save')" />
          </div>
        </div>
      </div>
      {!! Form::close()!!}
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>

<script type="text/javascript">
  $('#currency_code').select2({placeholder:"@lang('fleet.selectCurrency')"});

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  });

  $('.method').on('change',function() {
    if($('#stripe').is(":checked")){
      $('.stripe').removeAttr('readonly',true);
      $('.stripe').attr('required',true);
    }
    if($('#razorpay').is(":checked")){
      $('.razorpay').removeAttr('readonly',true);
      $('.razorpay').attr('required',true);
    }
    if(!$('#stripe').is(":checked")){
      $('.stripe').attr('readonly',true);
      $('.stripe').removeAttr('required',true);
    }
    if(!$('#razorpay').is(":checked")){
      $('.razorpay').attr('readonly',true);
      $('.razorpay').removeAttr('required',true);
    }
  });

  @if(Session::get('msg'))
    new PNotify({
      title: 'Success!',
      text: '{{ Session::get('msg') }}',
      type: 'success'
    });
  @endif
  @if(Session::get('error_msg'))
    new PNotify({
      title: 'Failed!',
      text: '{{ Session::get('error_msg') }}',
      type: 'error',
      delay: 15000
    });
  @endif

  @if(in_array("stripe", $methods))
    $('.stripe').removeAttr('readonly',true);
    $('.stripe').attr('required',true);
  @endif
  @if(in_array("razorpay", $methods))
    $('.razorpay').removeAttr('readonly',true);
    $('.razorpay').attr('required',true);
  @endif
</script>
@endsection
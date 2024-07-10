@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("customers.index")}}">@lang('fleet.customers')</a></li>
<li class="breadcrumb-item active">@lang('fleet.add_new')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.add_new')
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

        {!! Form::open(['route' => 'customers.store','method'=>'post']) !!}
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('company_name', __('fleet.companyName'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-home"></i></span>
                </div>
                  {!! Form::text('company_name', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('company_gst', __('fleet.companyGst'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-certificate"></i></span>
                </div>
                  {!! Form::text('company_gst', null,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('phone',__('fleet.phone'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                {!! Form::number('phone', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('email', __('fleet.email'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                {!! Form::email('email', null,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('address',__('fleet.address'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-address-book-o"></i></span>
                </div>
                {!! Form::textarea('address', null,['class' => 'form-control','size'=>'30x2','style'=>'resize:none;height:100px;']) !!}
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('opening_balance',__('fleet.opening_balance'), ['class' => 'form-label']) !!}
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-rupee"></i></span>
                    </div>
                    {!! Form::text('opening_balance', null,['class' => 'form-control opening_balance','id'=>'opening_balance','placeholer'=>'Enter Opening Balance','onkeypress'=>'return isNumberNegative(event,this)']) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('opening_remarks',"Opening Remarks", ['class' => 'form-label']) !!}
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fa fa-file-text-o"></i></span>
                    </div>
                    {!! Form::textarea('opening_remarks', null,['class' => 'form-control','size'=>'30x2','style'=>'resize:none;height:100px;']) !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
          {{-- <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('gender', __('fleet.gender') , ['class' => 'form-label']) !!}<br>
              <input type="radio" name="gender" class="flat-red gender" value="1" checked> @lang('fleet.male')<br>

              <input type="radio" name="gender" class="flat-red gender" value="0"> @lang('fleet.female')
            </div>
          </div> --}}
        </div>
        <div class="col-md-12">
          {!! Form::submit(__('fleet.add_new'), ['class' => 'btn btn-success']) !!}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
  // Check Number and Decimal
function isNumberNegative(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      // console.log(charCode);
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.//CHECK ONLY '-' Sign is allowed
          ((charCode < 48 && charCode != 45) || charCode > 57))
          return false;
          return true;
}
</script>
@endsection
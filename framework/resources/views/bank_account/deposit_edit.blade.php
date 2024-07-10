@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("bank-account.index")}}">@lang('fleet.bankAccount')</a></li>
<li class="breadcrumb-item active">Edit @lang('fleet.bankAccount')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Edit @lang('fleet.bankAccount')</h3>
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

        {!! Form::model($deposit,['route' => ['bank-account.deposit_save',$deposit->id],'files'=>true,'method'=>'PATCH']) !!}
        <div class="row toprow">
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('bank','Bank',['class' => 'form-label'])!!}
                    {!!Form::select('bank',$banks,$bankSelect,['class'=>'form-control','id'=>'bank','placeholder'=>'Select Bank','required'])!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('is_self','Is Self ?',['class' => 'form-label'])!!}
                    {!!Form::select('is_self',$is_self,!empty($deposit->refer_bank) ? 1 : null,['class'=>'form-control','id'=>'is_self','placeholder'=>'Is Self'])!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('amount','Amount',['class' => 'form-label'])!!}
                    {!!Form::text('amount',null,['class'=>'form-control','id'=>'amount','placeholder'=>'Enter Amount','required'])!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!!Form::label('date','Date',['class' => 'form-label'])!!}
                    {!!Form::text('date',Helper::indianDateFormat($deposit->date),['class'=>'form-control','id'=>'date','required','readonly'])!!}
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    {!!Form::label('remarks','Remarks',['class' => 'form-label'])!!}
                    {!!Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','style'=>'height:100px;resize:none;'])!!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'sub']) !!}
                </div>
            </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#driver_id").select2();
  $("#driver_id").on("change",function(){
        var darr = $(this).val();
        // console.log(darr)
        $('.remarks').load('{{url("admin/bank-account/get_remarks")}}/'+darr,function(result){
            console.log(result);
        })
    })
//   $("#sub").click(function(){
//     if($("#date").val()=="" || $("#date").val()==null){
//       alert("Date cannot be empty");
//       $("#date").css('border','1px solid red').focus();
//       return false;
//     }else{
//       $("#date").css('border','');
//     }
//   });
  $('#date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });

});
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })
</script>
@endsection
@extends('layouts.app')
@section('extra_css')
  <!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
  .emi_pay{color: white !important}
  #remarks{resize: none;height: 100px;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("vehicle-emi.index")}}">@lang('menu.vehicle_emi')</a></li>
<li class="breadcrumb-item active">Pay @lang('menu.vehicle_emi')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">Pay @lang('menu.vehicle_emi')</h3>
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

        {!! Form::open(['route' => 'vehicle-emi.search','files'=>true,'method'=>'post']) !!}
        <div class="row toprow">
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::select('vehicle_id',$vehicles,old('vehicle_id') ?? $request['vehicle_id'],['class' => 'form-control drivers','required','id'=>'vehicle_id','placeholder'=>'Please select a Vehicle']) !!}
            </div>
          </div>
            <div class="col-md-12">
            {!! Form::submit('Search', ['class' => 'btn btn-success','id'=>'sub']) !!}
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-12">
    @if(isset($result))
          <div class="row">
            <div class="col-md-12">
              <div class="card card-success">
                <div class="card-header">
                  <h3 class="card-title">
                    Search Result
                  </h3>
                </div>

                <div class="card-body table-responsive">
                  <table class="table table-bordered table-striped table-hover"  id="myTable">
                    <thead>
                      <tr>
                        <th>SL#</th>
                        <th>Date</th>
                        <th>Vehicle</th>
                        <th>Driver</th>
                        <th>Amount</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($emi_array as $k=>$row)
                      <tr>
                        <td>{{$k+1}}</td>
                        <td>{{Helper::getCanonicalDate($row->date,'default')}}</td>
                        <td>
                          @if($row->is_paid)
                          {{$row->vehicle->license_plate}}
                          @else
                          {{$row->vehicle}}
                          @endif
                        </td>
                        <td>{{!empty($row->driver_id) ? $row->driver->name : '-'}}</td>
                        <td>{{Hyvikk::get('currency')}} {{bcdiv($row->amount,1,2)}}</td>
                        <td>
                          @if($row->is_paid)
                            <span class="badge badge-success"><i class="fa fa-check"></i> Completed</span>
                          @else
                            {{-- <button type="button" class="btn btn-primary emi_pay">Pay</button> --}}
                            @if ($row->is_eligible)
                              <a class="btn btn-primary emi_pay" data-purch="{{$row->purchase_id}}" data-vehicle="{{$row->vehicle_id}}" data-date="{{Helper::getCanonicalDate($row->date,'default')}}"  data-toggle="modal" data-target="#payModal" title="Pay EMI">Pay</a>
                            @else
                                <span class="badge badge-info">Not Yet</span>
                            @endif
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                    {{-- <tfoot>
                      <tr>
                        <th>Vendor Name</th>
                        <th>Vehicle</th>
                        <th>Salary</th>
                        <th>Amount</th>
                      </tr>
                    </tfoot> --}}
                  </table>
                </div>
              </div>
            </div>
          </div>
          @endif
        {!! Form::close() !!}
  </div>

</div>
<!-- Modal -->
<div id="payModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h4 class="modal-title">Pay EMI</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div> --}}
    </div>
  </div>
</div>
@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
  $("#vehicle_id").select2();
  $(".emi_pay").click(function(){
    var purch = $(this).data("purch");
    var vehicle = $(this).data("vehicle");
    var date = $(this).data("date");
    
    var dataSet = {_token:"{{csrf_token()}}",purch:purch,vehicle:vehicle,date:date};
    $.post("{{route('vehicle-emi.pay-show')}}",dataSet).done(function(result){
      console.table(result);
      $("#payModal .modal-body").html(result);
    })
  })
  // $("#sub").click(function(){
  //   if($("#date").val()=="" || $("#date").val()==null){
  //     alert("Date cannot be empty");
  //     $("#date").css('border','1px solid red').focus();
  //     return false;
  //   }else{
  //     $("#date").css('border','');
  //   }
  // });
  $('#date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy'
  });

  $("body").on("focus","#pay_date",function(){
    $(this).datepicker({autoclose: true,format: 'dd-mm-yyyy'});
  })

  $("body").on("click","#payEmi",function(){
    var blankTest = /\S/;
    var purchase_id = $("#purchase_id").val();
    var vehicle_id = $("#vehicle_id").val();
    var date = $("#date").val();
    var amount = $("#amount").val();
    var pay_date = $("#pay_date").val();
    var bank = $("#bank").val();
    var method = $("#method").val();
    var reference_no = $("#reference_no").val();
    var remarks = $("#remarks").val();

    if(!blankTest.test(pay_date)){
      alert("Please select Pay Date");
      $("#pay_date").focus();
      return false;
    }
    if(!blankTest.test(bank)){
      alert("Please select Bank");
      $("#bank").focus();
      return false;
    }
    if(!blankTest.test(method)){
      alert("Please select Method of Payment");
      $("#method").focus();
      return false;
    }
    if(!blankTest.test(reference_no)){
      alert("Please enter Reference No");
      $("#reference_no").focus();
      return false;
    }

    if(confirm("Are you sure to Pay EMI")){
      //write for save
      var dataSet = {_token:"{{csrf_token()}}",purchase_id:purchase_id,vehicle_id:vehicle_id,date:date,amount:amount,pay_date:pay_date,bank_id:bank,method:method,reference_no:reference_no,remarks:remarks};
      $.post("{{route('vehicle-emi.store')}}",dataSet).done(function(result){
        // console.table(result);return false;
        if(result.status){
          alert(result.msg)
          $("#payModal").modal('hide');
          $('a[data-date='+result.date+']').replaceWith("<label class='badge badge-success'><i class='fa fa-check'></i> Paid</label>")
          // $("#payEmi").replaceWith("<label class='badge badge-success'><i class='fa fa-check'></i> Paid</label>")
        }
      })
    }
  })
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
@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-ui/jquery-ui.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item "><a href="{{ route("bookings.index")}}">@lang('menu.bookings')</a></li>
<li class="breadcrumb-item active">Renew Documents</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          Renew Documents
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

        {!! Form::open(['route' => 'vehicle-docs.store','method'=>'post','id'=>'bookingForm','files'=>true]) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        {!! Form::hidden('status',0)!!}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('vehicle_id',__('fleet.select_vehicle'), ['class' => 'form-label']) !!}
              {!!Form::select('vehicle_id[]',$vehicles,null,['class'=>'form-control','id'=>'vehicle_id','required','multiple'=>'multiple'])!!}
            </div>
          </div>
        </div>
        <div class="blank"></div>
        <div class="row">
          {{-- <div class="col-md-12">
            <div class="form-group">
              <table class="table table-bordered table-striped">
                <thead>
                    <th>Payment Method</th>
                    <th>DD No./Cheque No.</th>
                </thead>
                <tbody>
                    <td>
                        {!! Form::select("method",$method,null,['class' => 'form-control method','required','placeholder'=>'Select Payment Method','style'=>'margin-top:10px;']) !!}
                    </td>
                    <td>
                        {!! Form::text("ddno",null,['class' => 'form-control ddno','required','style'=>'margin-top: 9px;']) !!}
                    </td>
                </tbody>
            </table>
            </div>
          </div> --}}
          {{-- <div class="col-md-12">
            <div class="form-group">
              {!! Form::submit('Confirm Renew', ['class' => 'btn btn-success','id'=>'btnsub']) !!}
            </div>
          </div> --}}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>

<script type="text/javascript">
// Check Number and Decimal
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (            
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
        return true;
}

$(document).ready(function() {

  $("#btnsub").click(function(){
    if($(".date").length){
      $(".date").each(function(){
        if($(this).val()==''){
          alert("Please select date")
          $(this).focus();
          return false;
        }
      })
    }else{
      alert("Selected vehicles are not renewable")
      return false;
    }
  })

  $("body").on("click",".renew_btn",function(){
    var self = $(this);
    var blankTest = /\S/;
    var date = self.closest("tr").find(".date");
    var amount = self.closest("tr").find(".amount");
    var vendor = self.closest("tr").find(".vendor");
    var bank = self.closest("tr").find(".bank");
    var method = self.closest("tr").find(".method");
    var ddno = self.closest("tr").find(".ddno");
    var singleVehicleId = self.closest("tr").find(".singleVehicleId");
    var remarks = self.closest("tr").find(".remarks");
    var doc_id = date.data("doc");

    if(!blankTest.test(date.val())){
      alert("Please select date");
      date.focus();
      return false;
    }
    if(!blankTest.test(amount.val())){
      alert("Please enter amount");
      amount.focus();
      return false;
    }
    if(!blankTest.test(vendor.val())){
      alert("Please select vendor");
      vendor.focus();
      return false;
    }
    if(!blankTest.test(bank.val())){
      alert("Please select bank");
      bank.focus();
      return false;
    }
    if(!blankTest.test(method.val())){
      alert("Please select method");
      method.focus();
      return false;
    }
    if(!blankTest.test(ddno.val())){
      alert("Please enter reference no");
      ddno.focus();
      return false;
    }

    if(confirm("Are you sure ?")){
      var dataSet = {_token:"{{csrf_token()}}",date:date.val(),amount:amount.val(),vendor:vendor.val(),bank:bank.val(),method:method.val(),ddno:ddno.val(),vehicle_id:singleVehicleId.val(),doc_id:doc_id,remarks:remarks.val()}
      // console.table(dataSet);
      // return false;
      $.post("{{route('vehicle-docs.single-save')}}",dataSet).done(function(result){
        console.table(result);
        if(result.status){
          alert(result.msg)
          self.replaceWith("<label class='badge badge-success'>Completed</label>");
        }else{
          self.prop("disabled",true)
        }
      })
    }
  })

   $("body").on("focus",".date",function(){
    var self = $(this);
    $(this).datepicker({ 
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
      onSelect: function(date){
        // console.log(date);
        var vid = $(this).data("id");
        var ddoc = $(this).data("doc");
        var dataSet = {_token:"{{csrf_token()}}",date:date,vehicle_id:vid,doc_id:ddoc}; 
        // console.log(dataSet);
          $.ajax({
              type:"POST",
              url:"{{route('vehicle-docs.getNext')}}",
              data:dataSet,
              success: function(result){
                // console.log(result)
                if(self.next().length)
                  self.next().remove();
                self.after(result);
              }
          });
      }
    });
   })
   $("#vehicle_id").select2({
     placeholder : 'Please select a vehicle',
   });
   
   $("#vehicle_id").on("change",function(){
        var darr = $(this).val();
        // console.log(typeof darr)
        if(darr.length==0) $('.blank').html('');
        else {
          // console.log(darr)
          $('.blank').load('{{url("admin/vehicle-docs/renew-vehicles")}}/'+darr,function(result){
              // console.log(result);
          })
        };
    })

});
</script>
@endsection
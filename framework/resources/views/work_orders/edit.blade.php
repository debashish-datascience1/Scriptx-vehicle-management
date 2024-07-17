@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
  .btn-vertical-align{margin-top: 32px;}
  .description{height: 100px;resize: none;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item "><a href="{{ route("work_order.index")}}"> @lang('fleet.work_orders') </a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_workorder')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_workorder')</h3>
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

        {!! Form::open(['route' => ['work_order.update',$data->id],'method'=>'PATCH','files'=>true]) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        {!! Form::hidden('id',$data->id)!!}
        {!! Form::hidden('type','Updated')!!}

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('bill_image', "Bill/bill", ['class' => 'form-label']) !!}
              {!! Form::file('bill_image',['class' => 'form-control bill','accept'=>'.pdf,.doc,.png,.jpg,.jpeg,.doc,.docx']) !!}
              @if($data->bill_image != null)
                  <a href="{{ asset('uploads/'.$data->bill_image) }}" target="_blank" class="col-xs-3 control-label">View</a>
              @endif
            </div>
            <div class="form-group">
              {!! Form::label('vehicle_id',__('fleet.vehicle'), ['class' => 'form-label']) !!}
              <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vehicles as $vehicle)
                <option value="{{$vehicle->id}}" @if($vehicle->id == $data->vehicle_id) selected @endif> {{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              {!! Form::label('required_by', "Date", ['class' => 'form-label']) !!}
              <div class="input-group date">
              <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></div>
              {!! Form::text('required_by',Helper::indianDateFormat($data->required_by),['class'=>'form-control','required','readonly']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('meter',Hyvikk::get('dis_format')." ".__('fleet.reading'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
              <div class="input-group-prepend">
              <span class="input-group-text">{{Hyvikk::get('dis_format')}}</span></div>
              {!! Form::number('meter',$data->meter,['class'=>'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('note',__('fleet.note'), ['class' => 'form-label']) !!}
              {!! Form::textarea('note',$data->note,['class'=>'form-control','size'=>'30x4','style'=>'resize:none;']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('bill_no',"Bill No", ['class' => 'form-label']) !!}
              {!! Form::text('bill_no',$data->bill_no,['class'=>'form-control bill_no','id'=>'bill_no','placeholder'=>'Enter Bill No...']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']) !!}
              <select id="vendor_id" name="vendor_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vendors as $vendor)
                <option value="{{$vendor->id}}" @if($vendor->id == $data->vendor_id) selected @endif>{{$vendor->name}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              {!! Form::label('status',__('fleet.status'), ['class' => 'form-label']) !!}
              {!! Form::select('status',["Pending"=>"Pending", "Processing"=>"Processing", "Completed"=>"Completed","Hold"=>"Hold"],$data->status,['class' => 'form-control','required']) !!}
            </div>

            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  {!! Form::label('order_head',__('fleet.order_head'), ['class' => 'form-label']) !!}
                  {!! Form::select('category_id',$orderHeads,$data->category_id,['class' => 'form-control','required','placeholder'=>'Select Order Head']) !!}
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  {!! Form::label('price',__('fleet.work_order_price'), ['class' => 'form-label']) !!}
                  <div class="input-group mb-3">
                  <div class="input-group-prepend date">
                  <span class="input-group-text">{{Hyvikk::get('currency')}}</span>
                  </div>
                  {!! Form::number('price',$data->price,['class'=>'form-control','onkeypress'=>'return isNumber(event,this)','required']) !!}
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('description',__('fleet.description'), ['class' => 'form-label']) !!}
              {!! Form::textarea('description',$data->description,['class'=>'form-control','size'=>'30x4','style'=>'resize:none;']) !!}
            </div>
          </div>
        </div>
        <hr>
        <div class="row" style="margin-bottom: 25px;">
          <div class="col-md-2"> 
            <div class="form-group"> 
              @php
                  $select = !empty($data->parts) ? 1 : 2;
              @endphp
              {!! Form::label('is_addParts',"Add Parts ?", ['class' => 'form-label']) !!}
              {!! Form::select('is_addParts',[2=>'No',1=>'Yes'],$select,['class'=>'form-control','id'=>'is_addParts']) !!}
            </div>
          </div>
        </div>
        <div class="parts-div">
          @if (!empty($data->parts))
            @foreach ($data->parts as $key=>$item)
            <div class="fullPartsRow" data-stock='{{ $partsWithStock }}'>
            <!-- <div class="fullPartsRow"> -->
              <div class="row parts-row">
                  <div class="col-md-3">
                      <div class="form-group">
                          {!! Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']) !!}
                          {!! Form::select('parts_id[]',$options,$item->part_id,['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']) !!}
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          {!! Form::label('is_own',"Own Stock ?", ['class' => 'form-label']) !!}
                          {!! Form::select('is_own[]',[2=>'No',1=>'Yes'],$item->is_own,['class'=>'form-control is_own','id'=>'is_own','required']) !!}
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          {!! Form::label('qty',"Quantity", ['class' => 'form-label']) !!}
                          {!! Form::text('qty[]',$item->qty,['class'=>'form-control qty','id'=>'qty','placeholder'=>'Pieces','required','onkeypress'=>'return isNumberOnly(event)']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']) !!}
                          {!! Form::select('tyre_numbers[]', [], $item->tyre_used, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers','multiple' => 'multiple', 'placeholder' => 'Select Tyre Number', 'data-selected' => $item->tyre_used]) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']) !!}
                          {!! Form::text('unit_cost[]',$item->price,['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','required','onkeypress'=>'return isNumber(event,this)']) !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          {!! Form::label('total_cost',"Amount", ['class' => 'form-label']) !!}
                          {!! Form::text('total_cost[]',$item->total,['class'=>'form-control total_cost','id'=>'total_cost','placeholder'=>'Total Amount','disabled']) !!}
                      </div>
                  </div>
                  @if($key==0)
                  <div class="col-md-1 btn-vertical-align">
                      <div class="form-group">
                          <button type="button" class="btn btn-primary addmore" id="addmore">
                              <span class="fa fa-plus"></span> Add More
                          </button>
                      </div>
                  </div>
                  @endif
                  <div class="col-md-1 btn-vertical-align">
                      <div class="form-group">
                          <button type="button" class="btn btn-danger remove" id="remove">
                              <span class="fa fa-minus"></span> Remove
                          </button>
                      </div>
                  </div>
              </div>

              <div class="row gst-row">
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                          {!! Form::text('cgst[]',$item->cgst,['class'=>'form-control cgst','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)',$item->is_own==2 ? 'required' : 'readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                          {!! Form::text('cgst_amt[]',$item->cgst_amt,['class'=>'form-control cgst_amt','id'=>'cgst_amt','readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                          {!! Form::text('sgst[]',$item->sgst,['class'=>'form-control sgst','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)',$item->is_own==2 ? 'required' : 'readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                          {!! Form::text('sgst_amt[]',$item->sgst_amt,['class'=>'form-control sgst_amt','id'=>'sgst_amt','readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('after_gst',"Amount (After GST)", ['class' => 'form-label']) !!}
                          {!! Form::text('after_gst[]',$item->grand_total,['class'=>'form-control after_gst','id'=>'after_gst','readonly']) !!}
                      </div>
                  </div>
              </div>
              <hr>
            </div>
            @endforeach
          @endif
        </div>
        <hr>
        <div class="row">
          <div class="col-md-4">
            {!! Form::label('billable_parts',"Billable Parts : ", ['class' => 'form-label billable_parts']) !!}
            {{Hyvikk::get('currency')}} {!! Form::label('parts_value',"0.00", ['class' => 'form-label parts_value']) !!}
          </div>
          <div class="col-md-4">
            {!! Form::label('nonbillable_parts',"Non-Billable Part : ", ['class' => 'form-label nonbillable_parts']) !!}
            {{Hyvikk::get('currency')}} {!! Form::label('non_wo_value',"0.00", ['class' => 'form-label non_wo_value']) !!}
          </div>
          <div class="col-md-4">
            {!! Form::label('workorder_price',"Total Work Order Price : ", ['class' => 'form-label workorder_price']) !!}
            {{Hyvikk::get('currency')}} {!! Form::label('wo_value',"0.00", ['class' => 'form-label wo_value']) !!}
          </div>
        </div>
        <div class="row">
          <div class="parts col-md-12"></div>
        </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning','id'=>'updatework']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="dropAdd" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 211%;margin-left: -155px;">
      <div class="modal-header">
        <h4 class="modal-title">Add Part</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>
@endsection


@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
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
function isNumberOnly(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function removeAddMore(self){
    if(confirm('Are you sure ?')){
      self.closest(".addedmore").remove();
      $("#cgst").trigger('keyup')
    }
  }

function getPartsPrices(){
  const billable = [];
  const nonbillable = [];
  const total = [];
  var woprice=  $("#price").val();
  $(".parts_id").each(function(){
    var owndiv = $(this).closest(".fullPartsRow");
    var isown = owndiv.find('.is_own').val();
    if(isown==1){
      var total_cost = owndiv.find(".total_cost").val();
      nonbillable.push(total_cost);
    }else{
      var total_cost = owndiv.find(".after_gst").val();
      billable.push(total_cost);
    }
    total.push(total_cost);
  })
  var data = {_token:"{{csrf_token()}}",billable:billable,nonbillable:nonbillable,price:woprice,total:total};
  console.log(data);
  $.post("{{route('work_order.othercalc')}}",data).done(function(result){
    console.log(result);
    $(".parts_value").html(result.billable);
    $(".non_wo_value").html(result.nonbillable);
    $(".wo_value").html(result.grandtotal);
  })
  // return data;
}
$(document).ready(function() {
  getPartsPrices();
  // $('#vehicle_id').select2({placeholder: "@lang('fleet.selectVehicle')"});
  // $('#vendor_id').select2({placeholder: "@lang('fleet.select_vendor')"});
  // $('#select_part').select2({placeholder: "@lang('fleet.selectPart')"});
  $('#required_by').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });
  $('#created_on').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy'
    });

    $("#vendor_id").change(function(){
    console.log($(this).val())
    if($(this).val()==7){
      $("#is_gst").val(2);
      $("#is_gst").trigger('change')
    }else{
      $("#is_gst").val(1);
      $("#is_gst").trigger('change')
    }
  })

  $("#is_addParts").change(function(){
    var is_add = $(this).val();
    var div_count = $('.fullPartsRow').length;
    var data = {_token:"{{csrf_token()}}",count:div_count};
    if(is_add==1){
      $.post("{{route('work_order.add_parts')}}",data).done(function(result){
        $(".parts-div").append(result);
      })
    }else{
      $(".parts-div").html('');
    }
  })

  $("body").on("change",".is_own",function(){
    var is_own = $(this).val();
    if(is_own==1){
      // $(this).closest(".fullPartsRow").find(".gst-row").hide();
      $(this).closest(".fullPartsRow").find("#cgst").prop({"required":false,"readonly":true});
      $(this).closest(".fullPartsRow").find("#sgst").prop({"required":false,"readonly":true});
    }else{
      // $(this).closest(".fullPartsRow").find(".gst-row").show();
      $(this).closest(".fullPartsRow").find("#cgst").prop({"required":true,"readonly":false});
      $(this).closest(".fullPartsRow").find("#sgst").prop({"required":true,"readonly":false});
    }
    getPartsPrices();
  })

  $("body").on("change",".parts_id",function(){
    if($(this).val()=='add_new'){
        $("#dropAdd .modal-body").load("{{route('work_order.new_part')}}",function(data){
          $("#dropAdd .modal-body").html(data);
          $("#dropAdd").modal('show');
        })
    }
  })

  $("body").on("click",".addmore",function(){
    var div_count = $('.fullPartsRow').length;
    var data = {_token:"{{csrf_token()}}",count:div_count};
    $.post("{{route('work_order.add_parts')}}",data).done(function(result){
      $(".parts-div").append(result);
    })
  })

  $("body").on("click",".remove",function(){
    if(confirm("Are you sure ?")){
      $(this).closest(".fullPartsRow").remove();
      var div_count = $(".fullPartsRow").length;
      div_count==0 ? $("#is_addParts").val(2) : $("#is_addParts").val(1);
      getPartsPrices();
    }
  })

  $("body").on("click","#savebtn",function(e){
    e.preventDefault();
    var blankTest = /\S/;
    var item = $("#item").val();
    var category_id = $("#category_id").val();
    var manufacturer = $("#manufacturer").val();
    var unit = $("#unit").val();
    var min_stock = $("#min_stock").val();
    var description = $("#description").val();

    if(!blankTest.test(item)){
      alert("Item cannot be empty");
      $("#item").focus();
      return false;
    }
    if(!blankTest.test(category_id)){
      alert("Please select category");
      $("#category_id").focus();
      return false;
    }
    if(!blankTest.test(manufacturer)){
      alert("Please select manufacturer");
      $("#manufacturer").focus();
      return false;
    }
    if(!blankTest.test(unit)){
      alert("Please select unit");
      $("#unit").focus();
      return false;
    }
    
    var data = {_token:"{{csrf_token()}}",item:item,category_id:category_id,manufacturer:manufacturer,unit:unit,min_stock:min_stock,description:description};
    $.post("{{route('work_order.new_part')}}",data).done(function(result){
      $("#dropAdd").modal('hide');
      $(".parts_id").each(function(){
        var prev = $(this).val();
        var selecthtml = $(this);
        $(this).html(result);
        if(prev=="add_new")
          selecthtml.val('');
        else
          selecthtml.val(prev)
      })
      console.log(result)
    })
  })

  $("#updatework").click(function(){
    var blankTest = /\S/;
    // var status = $("#status").val();
    // $("#required_by").prop({'required':false,'readonly':false})
    var required_by = $("#required_by").val();
    // console.log(typeof required_by)
    if(!blankTest.test(required_by)){
      alert("Date cannot be empty");
      $("#required_by").focus();
      // $("#required_by").prop({'required':true,'readonly':true})
      return false;
    }

    $(".parts_id").each(function(){
      if($(this).val()=='add_new'){
        alert("Select a valid part");
        $(this).focus();
        return false;
      }
    })
  })

  $("body").on("keyup",".qty,.unit_cost,.cgst,.sgst,#price",function(){
    var ownDiv = $(this).closest(".fullPartsRow");
    var qty = ownDiv.find(".qty").val();
    var unit_cost = ownDiv.find(".unit_cost").val();
    var cgst = ownDiv.find(".cgst").val();
    var sgst = ownDiv.find(".sgst").val();
    var is_own = ownDiv.find(".is_own").val();
    if(qty != "" && unit_cost!=""){
      var data = {_token:"{{csrf_token()}}",is_own:is_own,qty:qty,unit_cost:unit_cost,cgst:cgst,sgst:sgst};
      $.post("{{route('work_order.wo_calcgst')}}",data).done(function(result){
        // console.log(result);
        ownDiv.find(".total_cost").val(result.total);
        if(is_own!=1){
          ownDiv.find(".cgst_amt").val(result.cgst_amt);
          ownDiv.find(".sgst_amt").val(result.sgst_amt);
          ownDiv.find(".after_gst").val(result.grand_total);
          getPartsPrices();
        }
      })
    }
  })


  $("#is_gst").change(function(){
    var is_gst = $("#is_gst").val();
    var cgst = $("#cgst")
    var sgst = $("#sgst")
    // var cgst_amt = $("#cgst_amt")
    // var sgst_amt = $("#sgst_amt")
    if(is_gst==1){
      cgst.prop('readonly',false).prop('required',true)
      sgst.prop('readonly',false).prop('required',true)
    }else{
      cgst.prop('readonly',true).prop('required',false).val('')
      sgst.prop('readonly',true).prop('required',false).val('')
      $("#sgst_amt").val('')
      $("#cgst_amt").val('')
      $("#total_amount").val('');
    }
  })

  // $("#price").keyup(function(){
  //   console.log($(this).val())
  //   var price = $(this).val();
  //   $("#total_amount").val(parseFloat(price).toFixed(2));
  // })
  // $(document).on('keyup','#price,#cgst,#sgst',function(){
  //   var price = $("#price").val();
  //   var cgst = $("#cgst").val();
  //   // var cgst_amt = $("#cgst_amt").val();
  //   var sgst = $("#sgst").val();
  //   // var sgst_amt = $("#sgst_amt").val();
    
  //   var sendData = {_token:"{{csrf_token()}}",price:price,cgst:cgst,sgst:sgst};
  //   $.post("{{route('work_order.wo_gstcalculate')}}",sendData).done(function(data){
  //     // console.log(data)
  //     console.table(data)
  //     // if(!isNaN(data.total) && data.total!=0){
  //     //   $(".smallfuel").show()
  //     //   $(".fueltot").html(data.total)
  //     // }else{
  //     //   $(".smallfuel").hide()
  //     //   $(".fueltot").html('')
  //     // }

  //     if(!isNaN(data.cgstval) && data.cgstval!=0){
  //       $("#cgst_amt").val(data.cgstval)
  //     }else{
  //       $("#cgst_amt").val('')
  //     }

  //     if(!isNaN(data.sgstval) && data.sgstval!=0){
  //       $("#sgst_amt").val(data.sgstval)
  //     }else{
  //       $("#sgst_amt").val('')
  //     }

  //     if(!isNaN(data.grandtotal) && data.grandtotal!=0){
  //       $("#total_amount").val(data.grandtotal)
  //     }else{
  //       $("#total_amount").val('')
  //     }

        
  //   })
  // })

  //Flat green color scheme for iCheck
  // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
  //   checkboxClass: 'icheckbox_flat-green',
  //   radioClass   : 'iradio_flat-green'
  // });

  // $('.attach').on('click',function(){
  //   var field = $('#select_part').val();
  //   if(field == "" || field == null){
  //     alert('Select Part');
  //   }
  //   else{
  //     var qty=$('#select_part option:selected').attr('qty');
  //     var title=$('#select_part option:selected').attr('title');
  //     var price=$('#select_part option:selected').attr('price');
  //     // alert($('#select_part option:selected').attr('title'));
  //     // alert($('#select_part option:selected').attr('qty'));
  //     $(".parts").append('<div class="row col-md-12"><div class="col-md-4">  <div class="form-group"> <label class="form-label">@lang('fleet.selectPart')</label> <select  class="form-control" disabled>  <option value="'+field+'" selected >'+title+'</option> </select> </div></div> <div class="col-md-2">  <div class="form-group"> <label class="form-label">@lang('fleet.qty')</label> <input type="number" name="parts['+field+']" min="1" value="1" class="form-control calc" max='+qty+' required> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label">@lang('fleet.unit_cost')</label> <input type="number" value="'+price+'" class="form-control" disabled> </div></div><div class="col-md-2">  <div class="form-group"> <label class="form-label">@lang('fleet.total_cost')</label> <input type="number" value="'+price+'" class="form-control total_cost" disabled id="'+field+'"> </div></div> <div class="col-md-2"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
  //     $('#select_part').val('').change();
  //     $('.calc').on('change',function(){
  //       // alert($(this).val()*price);
  //       $('#'+field).val($(this).val()*price);
  //     });
  //   }
  // });
//   $(document).ready(function() {
//     function populateTyreNumbers(partId, tyreNumbersSelect) {
//     var selectedTyreNumber = tyreNumbersSelect.data('selected');
    
//     if (partId) {
//         $.ajax({
//             url: '{{ route("get.tyre.numbers") }}',
//             type: 'GET',
//             data: { part_id: partId },
//             success: function(data) {
//                 tyreNumbersSelect.empty();
//                 tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
//                 $.each(data, function(key, value) {
//                     var selected = (value == selectedTyreNumber) ? 'selected' : '';
//                     tyreNumbersSelect.append('<option value="' + value + '" ' + selected + '>' + value + '</option>');
//                 });
//             }
//         });
//     } else {
//         tyreNumbersSelect.empty();
//         tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
//     }
// }
//     $(document).on('change', '.parts_id', function() {
//         var partId = $(this).val();
//         var tyreNumbersSelect = $(this).closest('.row').find('.tyre_numbers');
//         populateTyreNumbers(partId, tyreNumbersSelect);
//     });

//     // Populate tyre numbers for existing parts on page load
//     $('.parts_id').each(function() {
//         var partId = $(this).val();
//         var tyreNumbersSelect = $(this).closest('.row').find('.tyre_numbers');
//         populateTyreNumbers(partId, tyreNumbersSelect);
//     });
// });

$(document).ready(function() {
    function initializePartRow(row) {
        var stockData = JSON.parse(row.attr('data-stock'));
        var tyreNumbersSelect = row.find('.tyre_numbers');
        var selectedTyreNumber = tyreNumbersSelect.data('selected');

        function populateTyreNumbers(partId, tyreNumbersSelect) {
            if (partId && partId !== 'add_new') {
                $.ajax({
                    url: '{{ route("get.tyre.numbers") }}',
                    type: 'GET',
                    data: { part_id: partId },
                    success: function(data) {
                        tyreNumbersSelect.empty();
                        tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
                        $.each(data, function(key, value) {
                            var selected = (value == selectedTyreNumber) ? 'selected' : '';
                            tyreNumbersSelect.append('<option value="' + value + '" ' + selected + '>' + value + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            } else {
                tyreNumbersSelect.empty();
                tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
            }
        }

        function updatePartDetails(row) {
            var partId = row.find('.parts_id').val();
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var unitCostInput = row.find('.unit_cost');
            var qtyInput = row.find('.qty');
            var totalCostInput = row.find('.total_cost');
            
            // Reset fields when changing parts
            if (!partId || partId === 'add_new') {
                unitCostInput.val('');
                qtyInput.val('');
                totalCostInput.val('');
            }
            
            populateTyreNumbers(partId, tyreNumbersSelect);
            updateQuantityStatus(row);
        }
        function updateQuantityStatus(row) {
            var partId = row.find('.parts_id').val();
            var isOwn = row.find('.is_own').val();
            var qtyInput = row.find('.qty');
            var totalCostInput = row.find('.total_cost');
            
            if (partId && partId !== 'add_new') {
                var stock = stockData[partId] !== undefined ? parseInt(stockData[partId]) : 0;
                
                if (isOwn == '1') { // Yes for Own Stock
                    if (stock <= 0) {
                        qtyInput.val('Out of Stock');
                        qtyInput.prop('disabled', true);
                    } else {
                        qtyInput.prop('disabled', false);
                        qtyInput.attr('data-max-stock', stock);
                    }
                    // Reset total cost when Own Stock is Yes
                    totalCostInput.val('0');
                } else { // No for Own Stock
                    qtyInput.prop('disabled', false);
                    qtyInput.removeAttr('data-max-stock');
                }
            } else {
                qtyInput.prop('disabled', false);
                qtyInput.removeAttr('data-max-stock');
            }
            calculateAmount(row);
        }

        function calculateAmount(row) {
            var isOwn = row.find('.is_own').val();
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var unitCost = parseFloat(row.find('.unit_cost').val()) || 0;
            var totalCost = row.find('.total_cost');

            if (isOwn == '1') { // Yes for Own Stock
                totalCost.val('0');
            } else {
                totalCost.val((qty * unitCost).toFixed(2));
            }
        }

        row.find('.parts_id').on('change', function() {
            updatePartDetails(row);
        });

        row.find('.is_own').on('change', function() {
            updateQuantityStatus(row);
        });

        row.find('.qty, .unit_cost').on('input', function() {
            calculateAmount(row);
        });

        // Initialize the row
        updatePartDetails(row);
    }

    // Initialize existing rows
    $('.fullPartsRow').each(function() {
        initializePartRow($(this));
    });

    // Handle dynamically added rows
    $('body').on('click', '.addmore', function() {
        var newRow = $(this).closest('.fullPartsRow').clone(true);
        newRow.find('input, select').val('');
        $(this).closest('.fullPartsRow').after(newRow);
        initializePartRow(newRow);
    });

    // Validate quantity on form submission
    $('form').on('submit', function(e) {
        var isValid = true;
        $('.qty').each(function() {
            var row = $(this).closest('.row');
            var isOwn = row.find('.is_own').val();
            var qty = parseInt($(this).val());
            var maxStock = parseInt($(this).attr('data-max-stock'));
            
            if (isOwn == '1' && !isNaN(maxStock) && qty > maxStock) {
                alert('Input quantity (' + qty + ') is greater than the available stock (' + maxStock + ') for one or more parts.');
                isValid = false;
                return false;  // Break the loop
            }
        });
        if (!isValid) {
            e.preventDefault();  // Prevent form submission
        }
    });
});


});
</script>
@endsection
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
          <!-- <p>Debug Info:</p>
              <ul>
                  <li>Part ID: {{ $item->part_id }}</li>
                  <li>Quantity: {{ $item->qty }}</li>
                  <li>Unit Cost: {{ $item->price }}</li>
                  <li>Is Own: {{ $item->is_own }}</li>
                  <li>Tyre Used: {{ $item->tyre_used }}</li>
                  <li>Non-stock Tyre Numbers: {{ $item->non_stock_tyre_numbers }}</li>
              </ul> -->
              <div class="row parts-row">
                  <div class="col-md-3">
                      <div class="form-group">
                          {!! Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']) !!}
                          {!! Form::select('parts_id[]', $options, $item->part_id, ['class'=>'form-control parts_id','id'=>'parts_id','placeholder'=>'Select Part','required']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('is_own',"Own Stock ?", ['class' => 'form-label']) !!}
                          {!! Form::select('is_own[]',[2=>'No',1=>'Yes'], $item->is_own, ['class'=>'form-control is_own','id'=>'is_own','required']) !!}
                      </div>
                  </div>
                  <div class="col-md-1">
                      <div class="form-group">
                          {!! Form::label('qty',"Quantity", ['class' => 'form-label']) !!}
                          {!! Form::text('qty[]', $item->qty, ['class'=>'form-control qty','id'=>'qty','placeholder'=>'Pieces','required','onkeypress'=>'return isNumberOnly(event)']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('tyre_numbers', 'Tyre Numbers', ['class' => 'form-label']) !!}
                          {!! Form::select('tyre_numbers[]', [], $item->tyre_used, ['class' => 'form-control tyre_numbers', 'id' => 'tyre_numbers','multiple' => 'multiple', 'placeholder' => 'Select Tyre Number', 'data-selected' => $item->tyre_used]) !!}
                          <input type="hidden" name="tyre_numbers_grouped[]" class="tyre_numbers_grouped">
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('manual_tyre_numbers', 'Enter Tyre Numbers', ['class' => 'form-label']) !!}
                          {!! Form::text('manual_tyre_numbers[]', $item->is_own == 2 ? $item->non_stock_tyre_numbers : null, ['class' => 'form-control manual_tyre_numbers', 'id' => 'manual_tyre_numbers', 'placeholder' => 'Enter comma-separated numbers']) !!}
                          <input type="hidden" name="manual_tyre_numbers_grouped[]" class="manual_tyre_numbers_grouped">
                        </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('unit_cost',__('fleet.unit_cost'), ['class' => 'form-label']) !!}
                          {!! Form::text('unit_cost[]', $item->price, ['class'=>'form-control unit_cost','id'=>'unit_cost','placeholder'=>'Cost Per Unit','required','onkeypress'=>'return isNumber(event,this)']) !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          {!! Form::label('total_cost',"Amount", ['class' => 'form-label']) !!}
                          {!! Form::text('total_cost[]', $item->total, ['class'=>'form-control total_cost','id'=>'total_cost','placeholder'=>'Total Amount','disabled']) !!}
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
                          {!! Form::text('cgst[]', $item->cgst, ['class'=>'form-control cgst','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)',$item->is_own==2 ? 'required' : 'readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                          {!! Form::text('cgst_amt[]', $item->cgst_amt, ['class'=>'form-control cgst_amt','id'=>'cgst_amt','readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                          {!! Form::text('sgst[]', $item->sgst, ['class'=>'form-control sgst','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)',$item->is_own==2 ? 'required' : 'readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                          {!! Form::text('sgst_amt[]', $item->sgst_amt, ['class'=>'form-control sgst_amt','id'=>'sgst_amt','readonly']) !!}
                      </div>
                  </div>
                  <div class="col-md-2">
                      <div class="form-group">
                          {!! Form::label('after_gst',"Amount (After GST)", ['class' => 'form-label']) !!}
                          {!! Form::text('after_gst[]', $item->grand_total, ['class'=>'form-control after_gst','id'=>'after_gst','readonly']) !!}
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
$(document).ready(function() {
  $('.fullPartsRow').each(function() {
        if (!$(this).data('initialized')) {
            initializePartRow($(this));
            $(this).data('initialized', true);
        }
    });
    function initializePartRow(row) {
        var stockData = JSON.parse(row.attr('data-stock'));
        var tyreNumbersSelect = row.find('.tyre_numbers');
        var selectedTyreNumber = tyreNumbersSelect.data('selected');

        var existingQty = row.find('.qty').val();
        var existingUnitCost = row.find('.unit_cost').val();
        var existingManualTyreNumbers = row.find('.manual_tyre_numbers').val();

        function populateTyreNumbers(partId, tyreNumbersSelect) {
            if (partId && partId !== 'add_new') {
                $.ajax({
                    url: '{{ route("get.edit.tyre.numbers") }}',
                    type: 'GET',
                    data: { part_id: partId },
                    success: function(data) {
                        tyreNumbersSelect.empty();
                        tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
                        
                        var selectedTyreNumbers = tyreNumbersSelect.data('selected').toString().split(',');
                        
                        $.each(data, function(key, value) {
                            var selected = (selectedTyreNumbers.indexOf(value.toString()) !== -1) ? 'selected' : '';
                            tyreNumbersSelect.append('<option value="' + value + '" ' + selected + '>' + value + '</option>');
                        });
                        updateGroupedTyreNumbers(tyreNumbersSelect.closest('.fullPartsRow')); // Add this line

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            } else {
                tyreNumbersSelect.empty();
                tyreNumbersSelect.append('<option value="">Select Tyre Number</option>');
                updateGroupedTyreNumbers(tyreNumbersSelect.closest('.fullPartsRow')); // Add this line

            }
        }
          function validateTyreNumbers(row) {
              var isOwn = row.find('.is_own').val();
              var qty = parseInt(row.find('.qty').val()) || 0;
              var manualTyreNumbers = row.find('.manual_tyre_numbers').val().trim();
              var isTyre = row.data('is-tyre');

              updateGroupedTyreNumbers(row); 

              if (isTyre && isOwn == '2') { // No for Own Stock and is a tyre
                  var tyreNumbersCount = manualTyreNumbers ? manualTyreNumbers.split(',').filter(n => n.trim() !== '').length : 0;
                  return tyreNumbersCount === qty;
              }
              return true;
          }

        function updateValidationUI(row) {
            var isValid = validateTyreNumbers(row);
            var qtyInput = row.find('.qty');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');

            if (!isValid) {
                qtyInput.addClass('is-invalid');
                manualTyreNumbers.addClass('is-invalid');
            } else {
                qtyInput.removeClass('is-invalid');
                manualTyreNumbers.removeClass('is-invalid');
            }
        }

        function updatePartDetails(row) {
            var partId = row.find('.parts_id').val();
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');
            
            row.find('.unit_cost, .qty, .total_cost').val('');
            manualTyreNumbers.val('');
            tyreNumbersSelect.empty();
    
            if (partId && partId !== 'add_new') {
                $.ajax({
                    url: '{{ route("get.part.category") }}',
                    type: 'GET',
                    data: { part_id: partId },
                    success: function(categoryData) {
                        row.data('is-tyre', categoryData.is_tyre);
                        
                        updateQuantityStatus(row);
                        
                        if (categoryData.is_tyre) {
                            populateTyreNumbers(partId, tyreNumbersSelect);
                        }
                        tyreNumbersSelect.off('change').on('change', function() {
                          updateGroupedTyreNumbers(row);
                          validateTyreNumbers(row);
                      });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        row.data('is-tyre', false);
                        updateQuantityStatus(row);
                    }
                });
            } else {
                row.data('is-tyre', false);
                updateQuantityStatus(row);
            }
        }

        function updateGroupedTyreNumbers(row) {
            var selectedTyreNumbers = row.find('.tyre_numbers').val() || [];
            var groupedTyreNumbers = selectedTyreNumbers.join(',');
            row.find('.tyre_numbers_grouped').val(groupedTyreNumbers);

            var manualTyreNumbers = row.find('.manual_tyre_numbers').val();
            row.find('.manual_tyre_numbers_grouped').val(manualTyreNumbers);
        }

        function updateQuantityStatus(row) {
            var partId = row.find('.parts_id').val();
            var isOwn = row.find('.is_own').val();
            var qtyInput = row.find('.qty');
            var unitCostInput = row.find('.unit_cost');
            var totalCostInput = row.find('.total_cost');
            var gstRow = row.next('.gst-row');
            var tyreNumbersSelect = row.find('.tyre_numbers');
            var manualTyreNumbers = row.find('.manual_tyre_numbers');
            var isTyre = row.data('is-tyre');
            
            if (partId && partId !== 'add_new') {
              var stock = stockData[partId] !== undefined ? parseInt(stockData[partId]) : 0;
              
              if (isTyre) {
                  if (isOwn == '1') { // Yes for Own Stock
                      if (stock <= 0) {
                          qtyInput.prop('disabled', true);
                          tyreNumbersSelect.prop('disabled', true);
                      } else {
                          qtyInput.prop('disabled', false).attr('data-max-stock', stock);
                          tyreNumbersSelect.prop('disabled', false);
                      }
                      unitCostInput.prop('disabled', true);
                      totalCostInput.prop('disabled', true);
                      gstRow.find('input').prop('disabled', true);
                      manualTyreNumbers.prop('disabled', true);
                  } else { // No for Own Stock
                      qtyInput.prop('disabled', false).removeAttr('data-max-stock');
                      unitCostInput.prop('disabled', false);
                      totalCostInput.prop('disabled', true);
                      gstRow.find('input').prop('disabled', false);
                      tyreNumbersSelect.prop('disabled', true);
                      manualTyreNumbers.prop('disabled', false);
                  }
              } else {
                  // Not a tyre part
                  if (isOwn == '1') { // Yes for Own Stock
                      if (stock <= 0) {
                          qtyInput.prop('disabled', true);
                      } else {
                          qtyInput.prop('disabled', false).attr('data-max-stock', stock);
                      }
                      unitCostInput.prop('disabled', true);
                      totalCostInput.prop('disabled', true);
                      gstRow.find('input').prop('disabled', true);
                  } else { // No for Own Stock
                      qtyInput.prop('disabled', false).removeAttr('data-max-stock');
                      unitCostInput.prop('disabled', false);
                      totalCostInput.prop('disabled', true);
                      gstRow.find('input').prop('disabled', false);
                  }
                  tyreNumbersSelect.prop('disabled', true);
                  manualTyreNumbers.prop('disabled', true);
              }
          } else {
              qtyInput.prop('disabled', false).removeAttr('data-max-stock');
              unitCostInput.prop('disabled', false);
              totalCostInput.prop('disabled', true);
              gstRow.find('input').prop('disabled', false);
              tyreNumbersSelect.prop('disabled', true);
              manualTyreNumbers.prop('disabled', true);
          }

          calculateAmount(row);
        }

        function calculateAmount(row) {
            var isOwn = row.find('.is_own').val();
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var unitCost = parseFloat(row.find('.unit_cost').val()) || 0;
            var totalCost = row.find('.total_cost');
            var gstRow = row.next('.gst-row');

            if (isOwn == '1') {
                totalCost.val('0');
                gstRow.find('input').val('').prop('disabled', true);
            } else {
                var amount = qty * unitCost;
                totalCost.val(amount.toFixed(2));

                var cgst = parseFloat(gstRow.find('.cgst').val()) || 0;
                var sgst = parseFloat(gstRow.find('.sgst').val()) || 0;

                var cgstAmount = (amount * cgst / 100).toFixed(2);
                var sgstAmount = (amount * sgst / 100).toFixed(2);
                var afterGstAmount = (amount + parseFloat(cgstAmount) + parseFloat(sgstAmount)).toFixed(2);

                gstRow.find('.cgst_amt').val(cgstAmount);
                gstRow.find('.sgst_amt').val(sgstAmount);
                gstRow.find('.after_gst').val(afterGstAmount);
            }
            updateValidationUI(row);
        }

        row.find('.parts_id').on('change', function() {
            updatePartDetails(row);
        });

        row.find('.is_own').on('change', function() {
            var isOwn = $(this).val();
            if (isOwn == '1') {  // If changed to "Yes"
                row.find('.cgst, .cgst_amt, .sgst, .sgst_amt, .after_gst').val('');
            }
            updateQuantityStatus(row);
        });

        row.find('.qty, .unit_cost, .cgst, .sgst, .manual_tyre_numbers').on('input', function() {
            calculateAmount(row);
        });

        row.find('.tyre_numbers').on('change', function() {
            updateGroupedTyreNumbers(row);
        });

        updatePartDetails(row);
        if (existingQty) row.find('.qty').val(existingQty);
        if (existingUnitCost) row.find('.unit_cost').val(existingUnitCost);
        if (existingManualTyreNumbers) row.find('.manual_tyre_numbers').val(existingManualTyreNumbers);
        
        // Recalculate amount after setting existing values
        calculateAmount(row);
        updateGroupedTyreNumbers(row); 
    }

    $('.fullPartsRow').each(function() {
        initializePartRow($(this));
    });

    $('body').on('click', '.addmore', function() {
        var newRow = $(this).closest('.fullPartsRow').clone(true);
        newRow.find('input, select').val('');
        $(this).closest('.fullPartsRow').after(newRow);
        initializePartRow(newRow);
    });

    $('form').on('submit', function(e) {
        var isValid = true;
        $('.fullPartsRow').each(function() {
            var row = $(this);
            var isOwn = row.find('.is_own').val();
            var qty = parseInt(row.find('.qty').val());
            var maxStock = parseInt(row.find('.qty').attr('data-max-stock'));
            
            if (isOwn == '1' && !isNaN(maxStock) && qty > maxStock) {
                alert('Input quantity (' + qty + ') is greater than the available stock (' + maxStock + ') for one or more parts.');
                isValid = false;
                return false;
            }
            
            if (!validateTyreNumbers(row)) {
                alert('The number of tyre numbers must match the quantity for non-own stock parts.');
                updateValidationUI(row);
                isValid = false;
                return false;
            }
            updateGroupedTyreNumbers(row);
        });
    
        if (!isValid) {
            e.preventDefault();
        }
    });
});
});
</script>
@endsection
@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("parts-invoice.index")}}">Manage @lang('fleet.parts_inv')</a></li>
<li class="breadcrumb-item active">Edit @lang('fleet.parts_inv')</li>
@endsection
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Edit @lang('fleet.parts_inv')</h3>
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

        {!! Form::open(['route' => ['parts-invoice.update',$data->id],'method'=>'PATCH','files'=>true]) !!}
        {!! Form::hidden("user_id",Auth::user()->id) !!}
        {!! Form::hidden("id",$data->id)!!}
        <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']) !!}
                <div class='input-group mb-3 date'>
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <span class="fa fa-calendar"></span>
                    </span>
                  </div>
                  {!! Form::text('dateofpurchase',$data->created_at,['class'=>'form-control dateofpurchase','required']) !!}
                </div>
              </div>
            </div>
        </div>
        
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('invoice', "Bill/Invoice", ['class' => 'form-label']) !!}
                {!! Form::file('invoice',['class' => 'form-control invoice','accept'=>'.pdf,.doc,.png,.jpg,.jpeg,.gif']) !!}
                @if($data->invoice != null)
                  <a href="{{ asset('uploads/'.$data->invoice) }}" target="_blank" class="col-xs-3 control-label">View</a>
                @endif
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('billno', __('fleet.billno'), ['class' => 'form-label']) !!}
                {!! Form::text('billno', $data->billno,['class' => 'form-control billno','required','placeholer'=>'e.g PAT-15']) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']) !!}
                {!! Form::select("vendor_id",$vendors,$data->vendor_id,['class'=>'form-control vendor_id','id'=>'vendor_id','placeholder'=>'Select Vendor','required']) !!}
              </div>
            </div>
          </div>
        
          @foreach($parts_details as $k=>$v)
            @if($k==0)
            <div class="" id="parts_form">
              <div class="row cal_div">
                <div class="col-md-12">
                  <div class="form-group">
                    {!! Form::label('item', __('fleet.item'), ['class' => 'form-label']) !!}
                    {!! Form::select('item[]',$items, $v->parts_id,['class' => 'form-control item','required','placeholder'=>'Select Part']) !!}
                  </div> 
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    {!! Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']) !!}
                    <div class="input-group date">
                      <div class="input-group-prepend">
                      <span class="input-group-text">{{Hyvikk::get('currency')}}</span> </div>
                      {!! Form::text('unit_cost[]', $v->unit_cost,['class' => 'form-control unit_cost','required','onkeypress'=>'return isNumber(event,this)']) !!}
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    {!! Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']) !!}
                    {!! Form::text('stock[]', $v->quantity,['class' => 'form-control stock','required','onkeypress'=>'return isNumber(event,this)']) !!}
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    {!! Form::label('tyre_number', __('fleet.tyre_number'), ['class' => 'form-label']) !!}
                    {!! Form::text('tyre_number[]', $v->tyre_numbers, ['class' => 'form-control tyre_number', 'placeholder' => 'Enter comma-separated tyre numbers']) !!}
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    {!! Form::label('total', __('fleet.total'), ['class' => 'form-label']) !!}
                    {!! Form::text('total[]',Helper::properDecimals($v->total),['class' => 'form-control total','onkeypress'=>'return isNumber(event,this)']) !!}
                  </div>
                </div>
              </div>
            </div>
            @else
            <div class="addmore_cont cal_div" style="width: 100%;">
              <hr>
                <div class="" id="parts_form">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        {!! Form::label('item', __('fleet.item'), ['class' => 'form-label']) !!}
                        {!! Form::select('item[]',$items, $v->parts_id,['class' => 'form-control item','required','placeholder'=>'Select Part']) !!}
                      </div> 
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        {!! Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']) !!}
                        <div class="input-group date">
                          <div class="input-group-prepend">
                          <span class="input-group-text">{{Hyvikk::get('currency')}}</span> </div>
                          {!! Form::text('unit_cost[]', $v->unit_cost,['class' => 'form-control unit_cost','required','onkeypress'=>'return isNumber(event,this)']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        {!! Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']) !!}
                        {!! Form::text('stock[]', $v->quantity,['class' => 'form-control stock','required','onkeypress'=>'return isNumber(event,this)']) !!} 
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          {!! Form::label('tyre_number', __('fleet.tyre_number'), ['class' => 'form-label']) !!}
                          {!! Form::text('tyre_number[]', $v->tyre_numbers, ['class' => 'form-control tyre_number', 'placeholder' => 'Enter comma-separated tyre numbers']) !!}
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        {!! Form::label('total', __('fleet.total'), ['class' => 'form-label']) !!}
                            {!! Form::text('total[]',Helper::properDecimals($v->total),['class' => 'form-control total','onkeypress'=>'return isNumber(event,this)']) !!}
                      </div>
                    </div>
                    <div class="row" style="width:100%;margin-bottom:10px;">
                      <div class="col-md-12">
                        <div class="text-right">
                          <button class="btn btn-danger remove" type="button" id="button_removeform" name="button">Remove</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            @endif
          @endforeach
          <div class="row more_less"></div>
        
          <hr>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('subtotal', __('fleet.sumtotal'), ['class' => 'form-label']) !!}
                {!! Form::text('subtotal', Helper::properDecimals($data->sub_total),['class' => 'form-control subtotal','readonly','onkeypress'=>'return isNumber(event,this)']) !!}
              </div>
            </div>
            <div class="col-md-12">
              <div class="text-right">
                <button class="btn btn-primary addmore" type="button" id="button_addform" name="button">{{ __('Add More') }}</button>
              </div>
            </div>
          </div>
        <div class="row mt-2">
          <div class="col-md-12">
            <div class="card card-solid">
              <div class="card-header">
                <h3 class="card-title">
                  GST
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        @php
                          $isGSTReq = $data->is_gst==1 ? 'required' : 'readonly';
                        @endphp
                        {!! Form::label('Is GST?',__('fleet.isGst'), ['class' => 'form-label']) !!}
                        {!! Form::select('is_gst',$is_gst,$data->is_gst,['class'=>'form-control','id'=>'is_gst','placeholder'=>'Select','required']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('cgst',$data->cgst,['class'=>'form-control','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)',"$isGSTReq"]) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('cgst_amt',$data->cgst_amt,['class'=>'form-control','id'=>'cgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('sgst',$data->sgst,['class'=>'form-control','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)',"$isGSTReq"]) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('sgst_amt',$data->sgst_amt,['class'=>'form-control','id'=>'sgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('total_amount',__('fleet.total_amount'), ['class' => 'form-label']) !!}
                        {!! Form::text('total_amount',$data->grand_total,['class'=>'form-control','id'=>'total_amount','readonly']) !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
              {!! Form::submit(__('fleet.savePartInv'), ['class' => 'btn btn-warning','id'=>'savebtn']) !!}
            </div>
        </div> 
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}"></script>
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
  $(function(){
      //add more
      // $('#button_addform').click(function(){
      //   $.post('{{ url("admin/parts-invoice/getparts_form")}}',{_token:"{{csrf_token()}}"},function(result){
      //     console.log(result)
      //     $(".more_less").append(result);
      //     $(".item:last").select2();
      //   });
      // });

      function initializeFormItems() {
          $(".cal_div").each(function() {
              var itemField = $(this).find('.item');
              itemField.trigger('change');
          });
      }

      $("body").on("click",".remove",function(){
        if(confirm("Are you sure ?"))
          $(this).closest(".addmore_cont").remove();
          $(".unit_cost:first").keyup();
          $("#cgst").keyup();
      })

      $("#vendor_id").select2({placeholder:"@lang('fleet.select_vendor')"});
      $(".item").select2();
      
        // $("body").on("click",".dateofpurchase",function(){
          $(".dateofpurchase").datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
          }});
        // })
        $(".cheque_draft_date").datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
          }});

        //Flat green color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass   : 'iradio_flat-green'
        });

        $("body").on("keyup", ".unit_cost, .stock", function() {
    var stock = $(this).closest('.cal_div').find('.stock').val();
    var unit_cost = $(this).closest('.cal_div').find('.unit_cost').val();

    if (stock && unit_cost && !isNaN(stock) && !isNaN(unit_cost)) {
      var total = parseFloat(stock) * parseFloat(unit_cost);
      $(this).closest('.cal_div').find('.total').val(parseFloat(total).toFixed(2));

      updateSubtotal();
    }
  });

  $("body").on("keyup", ".total", function() {
    var stock = $(this).closest('.cal_div').find('.stock').val();
    var total = $(this).val();

    if (stock && total && !isNaN(stock) && !isNaN(total)) {
      var ucost = parseFloat(total) / parseFloat(stock);
      $(this).closest('.cal_div').find('.unit_cost').val(parseFloat(ucost).toFixed(2));

      updateSubtotal();
    }
  });

  function updateSubtotal() {
    var sumtotal = 0;
    $(".total").each(function(i, e) {
      var thisval = $(this).val();
      if (thisval && !isNaN(thisval)) {
        sumtotal += parseFloat(thisval);
      }
    });
    $(".subtotal").val(sumtotal.toFixed(2));
    $("#cgst").keyup();
  }

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

        $(document).on('keyup','#cgst,#sgst',function(){
          var price = $(".subtotal").val();
          var cgst = $("#cgst").val();
          // var cgst_amt = $("#cgst_amt").val();
          var sgst = $("#sgst").val();
          // var sgst_amt = $("#sgst_amt").val();
          
          var sendData = {_token:"{{csrf_token()}}",price:price,cgst:cgst,sgst:sgst};
          $.post("{{route('work_order.wo_gstcalculate')}}",sendData).done(function(data){
            // console.log(data)
            console.table(data)

            if(!isNaN(data.cgstval) && data.cgstval!=0){
              $("#cgst_amt").val(data.cgstval)
            }else{
              $("#cgst_amt").val('')
            }

            if(!isNaN(data.sgstval) && data.sgstval!=0){
              $("#sgst_amt").val(data.sgstval)
            }else{
              $("#sgst_amt").val('')
            }

            if(!isNaN(data.grandtotal) && data.grandtotal!=0){
              $("#total_amount").val(data.grandtotal)
            }else{
              $("#total_amount").val('')
            }

              
          })
        })
        $("body").on("keyup", ".stock", function() {
        var stock = parseInt($(this).val());
        var tyreNumberField = $(this).closest('.cal_div').find('.tyre_number');
        var itemField = $(this).closest('.cal_div').find('.item');
        
        // Trigger the change event on the item field to re-evaluate the category
        itemField.trigger('change');
    });


$("body").on("change", ".item", function() {
    var itemId = $(this).val();
    var calDiv = $(this).closest('.cal_div');
    var tyreNumberField = calDiv.find('.tyre_number');
    var stockField = calDiv.find('.stock');

    if (itemId) {
        $.ajax({
            url: '{{ route("get.category.info") }}',
            type: 'GET',
            data: { item_id: itemId },
            success: function(response) {
                if (response.category_name.toLowerCase().includes('tyre')) {
                    tyreNumberField.prop('disabled', false);
                    updateTyreNumberPlaceholder(stockField, tyreNumberField);
                } else {
                    tyreNumberField.prop('disabled', true);
                    tyreNumberField.val('');
                    tyreNumberField.attr('placeholder', 'Not applicable for this item');
                }
            },
            error: function() {
                console.log('Error fetching category info');
            }
        });
    } else {
        tyreNumberField.prop('disabled', true);
        tyreNumberField.val('');
        tyreNumberField.attr('placeholder', 'Select an item first');
    }
});

$("body").on("keyup", ".stock", function() {
    var calDiv = $(this).closest('.cal_div');
    var tyreNumberField = calDiv.find('.tyre_number');
    
    if (!tyreNumberField.prop('disabled')) {
        updateTyreNumberPlaceholder($(this), tyreNumberField);
    }
});

function updateTyreNumberPlaceholder(stockField, tyreNumberField) {
    var stock = parseInt(stockField.val());
    if (stock && !isNaN(stock) && stock > 0) {
        tyreNumberField.attr('placeholder', 'Enter ' + stock + ' tyre numbers, comma-separated');
    } else {
        tyreNumberField.attr('placeholder', 'Enter comma-separated tyre numbers');
    }
}

$("#savebtn").click(function(e) {
    var isValid = true;
    
    $(".cal_div").each(function() {
        var stockField = $(this).find('.stock');
        var tyreNumberField = $(this).find('.tyre_number');
        var itemField = $(this).find('.item');
        var stock = parseInt(stockField.val());
        var itemId = itemField.val();
        
        if (!tyreNumberField.prop('disabled') && stock > 0) {
            var tyreNumbersArray = tyreNumberField.val().split(',').map(item => item.trim()).filter(item => item !== '');
            
            if (tyreNumbersArray.length !== stock) {
                alert('The number of tyre numbers (' + tyreNumbersArray.length + ') does not match the current stock (' + stock + ') for one of the tyre items. Please adjust either the stock or the tyre numbers.');
                isValid = false;
                return false;
            }
            
            tyreNumberField.attr('name', 'tyre_number[' + itemId + ']');
        }
    });

    if (!isValid) {
        e.preventDefault();
    }
});
initializeFormItems();

$('#button_addform').click(function(){
    $.post('{{ url("admin/parts-invoice/getparts_form")}}', {_token:"{{csrf_token()}}"}, function(result){
        $(".more_less").append(result);
        $(".more_less .cal_div:last").find(".item").select2().trigger('change');  // Initialize Select2 for the new item only
    });
});

    
  })
  
</script>
@endsection
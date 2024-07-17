@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("parts-invoice.index")}}">Manage @lang('fleet.parts_inv')</a></li>
<li class="breadcrumb-item active">Add @lang('fleet.parts_inv')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Add @lang('fleet.parts_inv')</h3>
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

          {!! Form::open(['route' => 'parts-invoice.store','method'=>'post','files'=>true]) !!}
          {!! Form::hidden("user_id",Auth::user()->id) !!}
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
                {!! Form::text('dateofpurchase',date("d-m-Y"),['class'=>'form-control dateofpurchase','required']) !!}
              </div>
            </div>
             </div>
          </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('invoice', "Bill/Invoice", ['class' => 'form-label']) !!}
                  {!! Form::file('invoice',['class' => 'form-control invoice','accept'=>'.pdf,.doc,.png,.jpg,.jpeg,.gif']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('billno', __('fleet.billno'), ['class' => 'form-label']) !!}
                  {!! Form::text('billno', null,['class' => 'form-control billno','required','placeholer'=>'e.g PAT-15']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']) !!}
                  {!! Form::select("vendor_id",$vendors,null,['class'=>'form-control vendor_id','id'=>'vendor_id','placeholder'=>'Select Vendor','required']) !!}
                </div>
              </div>
            </div>
        

          <div class="" id="parts_form">
            <div class="row cal_div">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('item', __('fleet.item'), ['class' => 'form-label']) !!}
                  {!! Form::select('item[]',$items, null,['class' => 'form-control item','required','placeholder'=>'Select Part']) !!}
                </div> 
              </div>
              {{-- <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label ']) !!}
                    {!! Form::select("category_id[]",$categories,null,['class'=>'form-control category_id','required','placeholder'=>'Select Category']) !!}
                  </div>
              </div> --}}
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']) !!}
                  <div class="input-group date">
                    <div class="input-group-prepend">
                    <span class="input-group-text">{{Hyvikk::get('currency')}}</span> </div>
                    {!! Form::text('unit_cost[]', null,['class' => 'form-control unit_cost','required','onkeypress'=>'return isNumber(event,this)']) !!}
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']) !!}
                  {!! Form::text('stock[]', null,['class' => 'form-control stock','required','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('tyre_number', __('fleet.tyre_number'), ['class' => 'form-label']) !!}
                  {!! Form::text('tyre_number[]', null, ['class' => 'form-control tyre_number', 'disabled' => 'disabled', 'placeholder' => 'Enter comma-separated tyre numbers']) !!}
                </div>
              </div>
              <div class="col-md-4">  
                <div class="form-group">   
                  {!! Form::label('total', __('fleet.total'), ['class' => 'form-label']) !!}
                  {!! Form::text('total[]', null,['class' => 'form-control total','onkeypress'=>'return isNumber(event,this)']) !!}
                </div>
              </div>
            </div>
          </div>
          <div class="row more_less"></div>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('subtotal', __('fleet.sumtotal'), ['class' => 'form-label']) !!}
              {!! Form::text('subtotal', null,['class' => 'form-control subtotal','readonly','onkeypress'=>'return isNumber(event,this)']) !!}
            </div>
          </div>
          <!-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('cash_payment', __('fleet.cash_payment'), ['class' => 'form-label']) !!}
              {!! Form::text('cash_payment', null,['class' => 'form-control cash_payment','onkeypress'=>'return isNumber(event,this)']) !!}
            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('cheque_draft', __('fleet.cheque_draft'), ['class' => 'form-label']) !!}
              {!! Form::text('cheque_draft', null,['class' => 'form-control cheque_draft']) !!}
            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
                  {!! Form::label('cheque_draft_amount', __('fleet.cheque_draft_amount'), ['class' => 'form-label']) !!}
                  {!! Form::text('cheque_draft_amount', null,['class' => 'form-control cheque_draft_amount','onkeypress'=>'return isNumber(event,this)']) !!}
            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('cheque_draft_date',__('fleet.cheque_draft_date'), ['class' => 'form-label']) !!}
              {!! Form::text('cheque_draft_date',null,['class'=>'form-control cheque_draft_date']) !!}
            </div>
          </div> -->
          <!-- <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']) !!}
              <div class='input-group mb-3 date'>
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <span class="fa fa-calendar"></span>
                  </span>
                </div>
                {!! Form::text('dateofpurchase',date("d-m-Y"),['class'=>'form-control dateofpurchase','required']) !!}
              </div>
            </div>
          </div> -->
          <div class="col-md-12">
            <div class="text-right">
              <button class="btn btn-primary addmore" type="button" id="button_addform" name="button">{{ __('Add More') }}</button>
            </div>
          </div>
        </div>
        {{-- <div class="row mt-2">
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
                        {!! Form::label('Is GST?',__('fleet.isGst'), ['class' => 'form-label']) !!}
                        {!! Form::select('is_gst',$is_gst,null,['class'=>'form-control','id'=>'is_gst','placeholder'=>'Select']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('cgst',null,['class'=>'form-control','id'=>'cgst','placeholder'=>'Enter %','readonly']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('cgst_amt',null,['class'=>'form-control','id'=>'cgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('sgst',null,['class'=>'form-control','id'=>'sgst','placeholder'=>'Enter %','readonly']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('sgst_amt',null,['class'=>'form-control','id'=>'sgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('total_amount',__('fleet.total_amount'), ['class' => 'form-label']) !!}
                        {!! Form::text('total_amount',null,['class'=>'form-control','id'=>'total_amount','readonly']) !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
        <div class="row mt-3">
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
                        {!! Form::label('Is GST?',__('fleet.isGst'), ['class' => 'form-label']) !!}
                        {!! Form::select('is_gst',$is_gst,null,['class'=>'form-control','id'=>'is_gst','placeholder'=>'Select','required']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('cgst',__('fleet.cgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('cgst',null,['class'=>'form-control','id'=>'cgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('cgst_amt',__('fleet.cgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('cgst_amt',null,['class'=>'form-control','id'=>'cgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('sgst',__('fleet.sgst')." %", ['class' => 'form-label']) !!}
                        {!! Form::text('sgst',null,['class'=>'form-control','id'=>'sgst','placeholder'=>'Enter %','onkeypress'=>'return isNumber(event,this)','required']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="row">
                      <div class="col-md-6">
                        {!! Form::label('sgst_amt',__('fleet.sgst_amt'), ['class' => 'form-label']) !!}
                        {!! Form::text('sgst_amt',null,['class'=>'form-control','id'=>'sgst_amt','readonly']) !!}
                      </div>
                      <div class="col-md-6">
                        {!! Form::label('total_amount',__('fleet.total_amount'), ['class' => 'form-label']) !!}
                        {!! Form::text('total_amount',null,['class'=>'form-control','id'=>'total_amount','readonly']) !!}
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
              {!! Form::submit(__('fleet.savePartInv'), ['class' => 'btn btn-success','id'=>'savebtn']) !!}
            </div>
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

$("document").ready(function(){
  //add more
  $('#button_addform').click(function(){
    $.post('{{ url("admin/parts-invoice/getparts_form")}}',{_token:"{{csrf_token()}}"},function(result){
      // console.log(result)
      $(".more_less").append(result);
      $(".item:last").select2();
    });
  });

  $("body").on("click",".remove",function(){
    if(confirm("Are you sure ?"))
      $(this).closest(".addmore_cont").remove();
      $(".unit_cost:first").keyup();
      $("#cgst").keyup()
      updateSubtotal();

  })

  $("#vendor_id").select2({placeholder:"@lang('fleet.select_vendor')"});
  $(".item").select2();

    $(".dateofpurchase,.cheque_draft_date").datetimepicker({format: 'DD-MM-YYYY',sideBySide: true,icons: {
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

    // $("body").on("keyup",".unit_cost,.stock",function(){
    //   // alert($(this).closest('.cal_div').attr("class"));return false;
    //    var stock  = $(this).closest('.cal_div').find('.stock').val();
    //    // var stock  = $(this).parent().parent().find('.stock ').val();
    //    var unit_cost  = $(this).closest('.cal_div').find('.unit_cost ').val();
    //    var cash_payment=$('#cash_payment').val();
    //     //var unit_cost  = $(this).parent().parent().find('.unit_cost ').val();
    //     var total=parseFloat(stock)*parseFloat(unit_cost);
    //     var totalamnt  = $(this).closest('.cal_div').find('.total').val(parseFloat(total).toFixed(2));
    //     console.log(stock+" "+unit_cost+" "+total);
    //     var sumtotal=0;
    //     if(stock!="" && unit_cost!="")
    //     {
    //       $(".total").each(function(i,e){
    //         var thisval = $(this).val();
    //         if((thisval=="" && typeof thisval=='string') || (thisval==0 && typeof thisval=='string')){
    //           thisval=0.00;
    //         }
             
    //         thisval = parseFloat(thisval);
            
    //         if(thisval!="")
    //           sumtotal= parseFloat(sumtotal)+thisval;
          
    //       })
    //     }
    //     $(".subtotal").val(sumtotal.toFixed(2));
    //     $("#cgst").keyup()
    // });

    // $("body").on("keyup",".total,.stock",function(){
    //   // alert($(this).closest('.cal_div').attr("class"));return false;
    //    var stock  = $(this).closest('.cal_div').find('.stock').val();
    //    // var stock  = $(this).parent().parent().find('.stock ').val();
    //    var total  = $(this).closest('.cal_div').find('.total ').val();
    //   //  var cash_payment=$('#cash_payment').val();
    //     // var total  = $(this).parent().parent().find('.total ').val();
    //     console.log(stock+" "+total);
    //     if(total!=null && stock!=null){
    //       var ucost=parseFloat(total)/parseFloat(stock);
    //        $(this).closest('.cal_div').find('.unit_cost').val(parseFloat(ucost).toFixed(2));
    //       //console.log(stock+" "+ucost+" "+total);
    //       var sumtotal=0;
    //       if(stock!="" && total!="")
    //       {
    //         $(".total").each(function(i,e){
    //           var thisval = $(this).val();
    //           if((thisval=="" && typeof thisval=='string') || (thisval==0 && typeof thisval=='string')){
    //             thisval=0.00;
    //           }
              
    //           thisval = parseFloat(thisval);
              
    //           if(thisval!="")
    //             sumtotal= parseFloat(sumtotal)+thisval;
    //         })
    //       }
    //       $(".subtotal").val(sumtotal.toFixed(2));
    //       $("#cgst").keyup()
    //     }
    // });
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

  $("body").on("keyup", ".stock", function() {
  var stock = $(this).val();
  var tyreNumberField = $(this).closest('.cal_div').find('.tyre_number');
  
  if (stock && !isNaN(stock) && parseInt(stock) > 0) {
    tyreNumberField.prop('disabled', false);
    tyreNumberField.attr('placeholder', 'Enter ' + stock + ' tyre numbers, comma-separated');
  } else {
    tyreNumberField.prop('disabled', true);
    tyreNumberField.val('');
    tyreNumberField.attr('placeholder', 'Enter comma-separated tyre numbers');
  }
});

$("#savebtn").click(function(e) {
  var isValid = true;
  
  $(".stock").each(function() {
    var stock = parseInt($(this).val());
    var tyreNumbers = $(this).closest('.cal_div').find('.tyre_number').val();
    
    if (stock > 0) {
      var tyreNumbersArray = tyreNumbers.split(',').map(item => item.trim()).filter(item => item !== '');
      
      if (tyreNumbersArray.length !== stock) {
        alert('Please enter exactly ' + stock + ' tyre numbers for each item.');
        isValid = false;
        return false;
      }
    }
  });
  
  if (!isValid) {
    e.preventDefault();
  }
});

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
      $.post("{{route('parts-invoice.pi_gstcalculate')}}",sendData).done(function(data){
        // console.log(data)
        console.table(data)
        // if(!isNaN(data.total) && data.total!=0){
        //   $(".smallfuel").show()
        //   $(".fueltot").html(data.total)
        // }else{
        //   $(".smallfuel").hide()
        //   $(".fueltot").html('')
        // }

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

    // $(function(){
    //   $("body").on("click","#savebtn",function(){
    //     //e.preventDefault();
    //    var blankTest = /\S/;
    //    var billno = $('.billno').val();
    //    var vendor_id = $('.vendor_id').val();
    //    var title = $('.title');
    //    var number = $('.number');
    //    var category_id = $('.category_id');
    //    var manufacturer = $('.manufacturer');
    //    var unit_cost = $('.unit_cost');
    //    var stock = $('.stock');
    //    $(".more").remove();
    //    var returnval = true;
    //    console.log(billno);
    //    console.log(title.val());

    //    if(!blankTest.test(billno)){
    //     $('.billno').css('border','1px solid red').focus();
    //     alert("Bill No cannot be empty");
    //     return false;
    //    }else{ 
    //      $('.billno').css('border',''); 
    //      }

    //      if(!blankTest.test(vendor_id)){
    //         $('.vendor_id').css('border','1px solid red').focus();
    //         alert("Vendor name cannot be empty");
    //         return false;
    //       }else{ 
    //         $('.vendor_id').css('border',''); 
    //       }

    //    if(title.length>0){
    //     $('.title').each(function(){
    //       console.log($(this).val());
    //       if(!blankTest.test($(this).val())){
    //         $(this).css('border','1px solid red').focus();
    //         alert("Title cannot be empty");
    //         //i.preventDefault();
    //         returnval =  false;
    //       }else{ $(this).css('border',''); }
    //       })
    //       if(returnval===false) return returnval;
    //    }

    //    if(number.length>0){
    //     $('.number').each(function(){
    //       if(!blankTest.test($(this).val())){
    //         $(this).css('border','1px solid red').focus();
    //         alert("Number cannot be empty");
    //         returnval =  false;
    //     }else{ $(this).css('border',''); }
    //     })
    //     if(returnval===false) return returnval;
    //    }

    //    if(category_id.length>0){
    //     $('.category_id').each(function(e){
    //       if(!blankTest.test($(this).val())){
    //       $(this).css('border','1px solid red').focus();
    //       alert("Category cannot be empty");
    //       returnval =  false;
    //     }else{ $(this).css('border',''); }
    //     })
    //     if(returnval===false) return returnval;
    //    }

    //    if(manufacturer.length>0){
    //     $('.manufacturer').each(function(e){
    //       if(!blankTest.test($(this).val())){
    //       $(this).css('border','1px solid red').focus();
    //       alert("manufacturer cannot be empty");
    //       returnval =  false;
    //     }else{ $(this).css('border',''); }
    //     })
    //     if(returnval===false) return returnval;
    //    }
    //    if(unit_cost.length>0){
    //     $('.unit_cost').each(function(e){
    //       if(!blankTest.test($(this).val())){
    //       $(this).css('border','1px solid red').focus();
    //       alert("Unit cost cannot be empty");
    //       returnval =  false;
    //     }else{ $(this).css('border',''); }
    //     })
    //     if(returnval===false) return returnval;
    //    }
    //    if(stock.length>0){
    //     $('.stock').each(function(e){
    //       if(!blankTest.test($(this).val())){
    //       $(this).css('border','1px solid red').focus();
    //       alert("Stock cannot be empty");
    //       returnval =  false;
    //     }else{ $(this).css('border',''); }
    //     })
    //     if(returnval===false) return returnval;
    //    }
    //   // return false;
    //  })
    // })
})

 
</script>
@endsection
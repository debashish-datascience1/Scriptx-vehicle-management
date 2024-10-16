@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("parts.index")}}">@lang('menu.manageParts')</a></li>
<li class="breadcrumb-item active">@lang('fleet.editParts')</li>
@endsection
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.editParts')</h3>
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

        {!! Form::open(['route' => ['parts.update',$data->id],'method'=>'PATCH','files'=>true]) !!}
        {!! Form::hidden("user_id",Auth::user()->id) !!}
        {!! Form::hidden("id",$data->id)!!}
        
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                {!! Form::label('billno', __('fleet.billno'), ['class' => 'form-label']) !!}
                {!! Form::text('billno', $data->billno,['class' => 'form-control billno','required']) !!}
              </div>

            </div>
            <div class="col-md-6">
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
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
                    {!! Form::text('title[]', $v->title,['class' => 'form-control title','required']) !!}
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    {!! Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label ']) !!}
                    {!! Form::select("category_id[]",$categories,$v->parts_category,['class'=>'form-control category_id','required','placeholder'=>'Select Category']) !!}
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
                    <div class="col-md-6">
                      <div class="form-group">
                        {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
                        {!! Form::text('title[]', $v->title,['class' => 'form-control title','required']) !!}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        {!! Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label ']) !!}
                        {!! Form::select("category_id[]",$categories,$v->parts_category,['class'=>'form-control category_id','required','placeholder'=>'Select Category']) !!}
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
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('cash_payment', __('fleet.cash_payment'), ['class' => 'form-label']) !!}
                {!! Form::text('cash_payment', $data->cash_amount,['class' => 'form-control cash_payment','onkeypress'=>'return isNumber(event,this)']) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('cheque_draft', __('fleet.cheque_draft'), ['class' => 'form-label']) !!}
                {!! Form::text('cheque_draft', $data->chq_draft_number,['class' => 'form-control cheque_draft']) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                    {!! Form::label('cheque_draft_amount', __('fleet.cheque_draft_amount'), ['class' => 'form-label']) !!}
                    {!! Form::text('cheque_draft_amount', $data->chq_draft_amount,['class' => 'form-control cheque_draft_amount','onkeypress'=>'return isNumber(event,this)']) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('cheque_draft_date',__('fleet.cheque_draft_date'), ['class' => 'form-label']) !!}
                {!! Form::text('cheque_draft_date',$data->chq_draft_date,['class'=>'form-control cheque_draft_date']) !!}
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('dateofpurchase',__('fleet.dateofpurchase'), ['class' => 'form-label']) !!}
                <div class='input-group mb-3 date'>
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <span class="fa fa-calendar"></span>
                    </span>
                  </div>
                  {!! Form::text('dateofpurchase[]',$data->created_at,['class'=>'form-control dateofpurchase','required']) !!}
                </div>
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
              {!! Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success','id'=>'savebtn']) !!}
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
      $('#button_addform').click(function(){
        $.post('{{ url("admin/parts/getparts_form")}}',{_token:"{{csrf_token()}}"},function(result){
          console.log(result)
          $(".more_less").append(result);
        });
      });

      $("body").on("click",".remove",function(){
        if(confirm("Are you sure ?"))
          $(this).closest(".addmore_cont").remove();
          $(".unit_cost:first").keyup();
          $("#cgst").keyup();
          // //After Removal set the Grand Total
          // var sumtotal=0;
          
          // $(".total").each(function(i,e){
          //   var thisval = $(this).val();
          //   if((thisval=="" && typeof thisval=='string') || (thisval==0 && typeof thisval=='string')){
          //     thisval=0.00;
          //   }
            
          //   thisval = parseFloat(thisval);
            
          //   if(thisval!="")
          //     sumtotal= parseFloat(sumtotal)+thisval;
          
          // })

          // $(".subtotal").val(sumtotal.toFixed(2));
          
      })

      $("#vendor_id").select2({placeholder:"@lang('fleet.select_vendor')"});
      
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

        // $("body").on("keyup",".unit_cost,.stock",function(){
        //   // alert($(this).closest('.cal_div').attr("class"));return false;
        //   var stock  = $(this).closest('.cal_div').find('.stock').val();
        //   // var stock  = $(this).parent().parent().find('.stock ').val();
        //   var unit_cost  = $(this).closest('.cal_div').find('.unit_cost ').val();
        //   var cash_payment=$('#cash_payment').val();
        //     //var unit_cost  = $sss(this).parent().parent().find('.unit_cost ').val();
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

        //       $(".subtotal").val(sumtotal.toFixed(2));
        //       $("#cgst").keyup()
        //     }
        // });

        $("body").on("keyup",".total,.stock",function(){
      // alert($(this).closest('.cal_div').attr("class"));return false;
       var stock  = $(this).closest('.cal_div').find('.stock').val();
       // var stock  = $(this).parent().parent().find('.stock ').val();
       var total  = $(this).closest('.cal_div').find('.total ').val();
      //  var cash_payment=$('#cash_payment').val();
        // var total  = $(this).parent().parent().find('.total ').val();
        if(total!=null && stock!=null){
          var ucost=parseFloat(total)/parseFloat(stock);
           $(this).closest('.cal_div').find('.unit_cost').val(parseFloat(ucost).toFixed(2));
          console.log(stock+" "+ucost+" "+total);
          var sumtotal=0;
          if(stock!="" && total!="")
          {
            $(".total").each(function(i,e){
              var thisval = $(this).val();
              if((thisval=="" && typeof thisval=='string') || (thisval==0 && typeof thisval=='string')){
                thisval=0.00;
              }
              
              thisval = parseFloat(thisval);
              
              if(thisval!="")
                sumtotal= parseFloat(sumtotal)+thisval;
            })
          }
          $(".subtotal").val(sumtotal.toFixed(2));
          $("#cgst").keyup()
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
          $.post("{{route('work_order.wo_gstcalculate')}}",sendData).done(function(data){
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
  })
  
</script>
@endsection
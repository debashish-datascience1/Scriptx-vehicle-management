@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item active">Manage @lang('menu.parts_inv')</li>
@endsection
@section('extra_css')
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
</style>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Manage @lang('menu.parts_inv')
          <a href="{{ route('parts-invoice.create')}}" class="btn btn-success">Add @lang('fleet.parts_inv')</a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>
                @if($data->count() > 0)
                  <input type="checkbox" id="chk_all">
                @endif
              </th>
              
              <th>@lang('fleet.billno')</th>
              <th>@lang('fleet.vendor')</th>
              <th style="width:53%">Details</th>           
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $row) 
            
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
              </td>
              <td> {{$row->billno}}
              </td>
              <td>{{$row->vendor->name}}
              <br>
              ({{$row->vendor->type}})
              <br>
              {{$row->vendor->phone}}
              </td>
              <td>
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Unit Cost</th>
                      <th>Quantity</th>
                      <th>Amount</th>
                      <th>Tyre No.</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($row->partsDetails as $dat)
                    <tr>
                      <td>
                        @if($dat->parts_zero)
                          {{$dat->parts_zero->item ?? 'N/A'}} 
                          {{$dat->parts_zero->category->name ?? 'N/A'}} 
                          ({{$dat->parts_zero->manufacturer_details->name ?? 'N/A'}})
                          
                        @else
                          N/A
                        @endif
                      </td>
                      <td>{{Hyvikk::get('currency')." ". $dat->unit_cost}}</td>
                      <td>{{$dat->quantity}}</td>
                      <td>{{Hyvikk::get('currency')." ". $dat->total}}</td>
                      <!-- <td>{{ $dat->tyre_numbers}}</td> -->
                      <td>
                        @php
                          $partsModel = App\Model\PartsModel::find($dat->parts_id);
                          $tyre_numbers = $partsModel ? $partsModel->tyre_numbers : '';
                          $numbers_array = explode(',', $tyre_numbers);
                          $formatted_numbers = [];

                          foreach (array_chunk($numbers_array, 4) as $chunk) {
                            $formatted_numbers[] = implode(', ', $chunk);
                          }

                          echo nl2br(implode("\n", $formatted_numbers));
                        @endphp
                      </td>
                    </tr>
                    @endforeach
                    <tr>
                      <th colspan="2"></th>
                      <th>Total</th>
                      <th>{{Hyvikk::get('currency')}}{{Helper::properDecimals($row->sub_total)}}</th>
                    </tr>
                    @if($row->is_gst==1 && !empty($row->cgst) && !empty($row->sgst))
                    <tr style="font-size: 14px;font-weight: 600;">
                      <td style="text-align: center;">CGST <br> SGST</td>
                      <td>{{$row->cgst}} % <br> {{$row->sgst}} %</td>
                      <td>{{Hyvikk::get('currency')}}{{Helper::properDecimals($row->cgst_amt)}} <br> {{Hyvikk::get('currency')}}{{Helper::properDecimals($row->sgst_amt)}}</td>
                      <td style="vertical-align: middle;font-size: 16px;">
                      {{Hyvikk::get('currency')}}{{Helper::properDecimals($row->cgst_amt + $row->sgst_amt)}}
                      </td>
                    </tr>
                    
                    <tr>
                      <th colspan="2"></th>
                      <th>Grand Total</th>
                      <th>{{Hyvikk::get('currency')}}{{Helper::properDecimals($row->grand_total)}}</th>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item vview" data-id="{{$row->id}}" data-toggle="modal" data-target="#PartsDetailsModal"> <span aria-hidden="true" class="fa fa-eye" style="color: green"></span> View</a>
                  @if(Helper::isEligible($row->id,26))
                  <a class="dropdown-item" href="{{ url("admin/parts-invoice/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"> <span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                 @endif
                </div>
              </div>
              {!! Form::open(['url' => 'admin/parts-invoice/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
              {!! Form::hidden("id",$row->id) !!}
              {!! Form::close() !!}
              </td>
            </tr>
            @endforeach
            
          </tbody>
          <tfoot>
            <tr>
              <th>
                {{-- @if($data->count() > 0)
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button>
                @endif --}}
              </th>
              <th>@lang('fleet.billno')</th>
              <th>@lang('fleet.vendor')</th>
              <th>Details</th>           
              <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal view-->
<div id="PartsDetailsModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width:158%">
      <div class="modal-header" style="border-bottom:none;">
        <h4 class="modal-title">Parts Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <div class="modal-body">
          Loading...
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      
    </div>
  </div>
</div>
<!-- Modal -->


<!-- Modal -->
<div id="stockModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="part_title"></span> : @lang('fleet.addStock')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      {!! Form::open(['method'=>'POST','url'=>'admin/add-stock']) !!}
      {!! Form::hidden('part_id',null,['id'=>'part_id']) !!}
      <div class="modal-body">
        <div class="form-group">
          {!! Form::label('stock', __('fleet.qty'), ['class' => 'form-label']) !!}
          {!! Form::number('stock', 1,['class' => 'form-control','min'=>1]) !!}
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-info" type="submit" data-submit="">@lang('fleet.addStock')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/delete-parts','method'=>'POST','id'=>'form_delete']) !!}
        <div id="bulk_hidden"></div>
        <p>@lang('fleet.confirm_bulk_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->


@endsection

@section('script')
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

 

  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "@lang('fleet.delete_error')",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


  $('#chk_all').on('click',function(){
    if(this.checked){
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",true);
      });
    }else{
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",false);
      });
    }
  });

  // Checkbox checked
  function checkcheckbox(){
    // Total checkboxes
    var length = $('.checkbox').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.checkbox').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });
    // console.log(length+" "+totalchecked);
    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#chk_all").prop('checked', true);
    }else{
        $('#chk_all').prop('checked', false);
    }
  }


  $('#stockModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#part_id").val(id);
    $("#part_title").html(e.relatedTarget.dataset.title);
  });
  $(".vview").on("click",function(){
    var id = $(this).data("id");
    $("#PartsDetailsModal .modal-body").load('{{url("admin/parts-invoice/view_event")}}/'+id,function(res){
      $("#PartsDetailsModal").modal({show:true})
    })
    
  })
</script>
@endsection
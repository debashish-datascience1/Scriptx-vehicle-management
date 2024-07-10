@extends("layouts.app")
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.work_orders')</li>
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
        <h3 class="card-title">
        @lang('fleet.work_orders')
        &nbsp;
        <a href="{{ route('work_order.create')}}" class="btn btn-success">@lang('fleet.create_workorder')</a></h3>
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
              <th>@lang('fleet.vehicle')</th>
              <th></th>
              <th>Date</th>
              <th>Transaction ID</th>
              <th>@lang('fleet.personnel')/@lang('fleet.description')</th>
              <th width="30%">@lang('fleet.work_order_price')</th>
              {{-- <th>@lang('fleet.gst')</th>
              <th>@lang('fleet.aftergst')</th> --}}
              {{-- <th>@lang('fleet.total') @lang('fleet.parts') @lang('fleet.cost')</th>
              <th>@lang('fleet.total_cost')</th> --}}
              <th>@lang('fleet.status')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($data as $row)
            <tr>
              <td>
                {{-- <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'> --}}
              </td>
              <td>
                @if($row->vehicle['vehicle_image'] != null)
                <img src="{{asset('uploads/'.$row->vehicle['vehicle_image'])}}" height="70px" width="70px">
                @else
                <img src="{{ asset("assets/images/vehicle.jpeg")}}" height="70px" width="70px">
                @endif
              </td>
              <td>
                {{strtoupper($row->vehicle['license_plate'])}}
              </td>
              <td>
                {{date($date_format_setting,strtotime($row->required_by))}}<br>
                <strong>{{$row->bill_no}}</strong>
                <br>
                @if($row->bill_image != null)
                  <a href="{{ asset('uploads/'.$row->bill_image) }}" target="_blank" class="col-xs-3 control-label">View</a>
                @endif
              </td>
              <td>
                {{$row->trash_id}}
                @if(!empty($row->category_id))
                <br>
                {{!empty($row->order_head) ? $row->order_head->name : 'n/a'}}
                @endif
              </td>
              <td>
                {{$row->vendor['name']}} <br>
                {{$row->description}}
              </td>
              <td> 
                <table class="table table-responsive table-bordered" style="display: table;">
                  <tr>
                    <th>Order Price</th>
                    <td colspan="2">
                      <label for="" style="float: right">{{Hyvikk::get('currency')}} {{$row->price}}</label>
                    </td>
                  </tr>
                  @if(!empty($row->parts_fromvendor))
                  <tr>
                    <th>
                      Parts Price
                      @if($row->is_itemno)
                        <a class="badge badge-primary" href='{{ url("admin/parts-used/".$row->id)}}'> Item No.</a>
                      @endif
                    </th>
                    <td colspan="2">
                      <label for="" style="float: right">{{Hyvikk::get('currency')}} {{ $row->parts_fromvendor->sum('total') }}</label>
                    </td>
                  </tr>
                  @endif
                  @if(!empty($row->parts_fromvendor))
                  <tr>
                    <th>GST</th>
                    <td style="text-align:right" class="font-italic font-weight-bold">{{Hyvikk::get('currency')}} 
                    @if (!empty($row->parts_fromvendor))
                      {{$row->parts_fromvendor->sum('cgst_amt')+$row->parts_fromvendor->sum('sgst_amt')}}
                    @else
                        
                    @endif 
                    </td>
                  </tr>
                  @endif
                  <tr>
                    <th>Total Price</th>
                    <td colspan="2"><label style="float: right;">{{Hyvikk::get('currency')}} {{ bcdiv($row->price + $row->parts_fromvendor->sum('grand_total'),1,2)}}</label></td>
                  </tr>
                  {{-- @endif --}}
                </table>
              </td>
              {{-- <td> {{Hyvikk::get('currency')}} {{ $row->parts->sum('total') }}</td>
              <td> {{Hyvikk::get('currency')}} {{ $row->price + $row->parts->sum('total') + $row->cgst_amt + $row->sgst_amt }}</td> --}}
              <td>
                @if($row->status == "Completed")
                <span class="text-success">{{$row->status}}</span>
                @elseif($row->status == "Pending")
                <span class="text-warning">{{$row->status}}</span>
                @else
                {{$row->status}}
                @endif
              </td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                <span class="fa fa-gear"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item vview" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> @lang('fleet.view')</a>
                  <a class="dropdown-item" href='{{ url("admin/parts-used/".$row->id)}}'> <span aria-hidden="true" class="fa fa-wrench" style="color: green;"></span> @lang('fleet.partsUsed')</a>
                  @if($row->status == "Completed" && empty($row->category_id))
                  <a class="dropdown-item vorderhead" data-id="{{$row->id}}" data-toggle="modal" data-target="#orderHeadModal"> <span aria-hidden="true" class="fa fa-level-down" style="color: #423091;"></span> Add Order Head</a>
                  @endif
                  @if(Helper::isEligible($row->id,28))
                  <a class="dropdown-item" href='{{ url("admin/work_order/".$row->id."/edit")}}'> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                  @endif
                </div>
              </div>
              {!! Form::open(['url' => 'admin/work_order/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
              {!! Form::hidden("id",$row->id) !!}
              {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>
                @if($data->count() > 0)
                  {{-- <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button> --}}
                @endif
              </th>
              <th>@lang('fleet.vehicle')</th>
              <th>License Plate</th>
              <th>@lang('fleet.created_on')/@lang('fleet.required_by')</th>
              <th>Transaction ID</th>
              <th>@lang('fleet.personnel')/@lang('fleet.description')</th>
              <th>@lang('fleet.work_order_price')</th>
              {{-- <th>@lang('fleet.gst')</th>
              <th>@lang('fleet.aftergst')</th> --}}
              {{-- <th>@lang('fleet.total') @lang('fleet.parts') @lang('fleet.cost')</th>
              <th>@lang('fleet.total_cost')</th> --}}
              <th>@lang('fleet.status')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

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
        {!! Form::open(['url'=>'admin/delete-work-orders','method'=>'POST','id'=>'form_delete']) !!}
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
<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.view')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          @lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<div id="orderHeadModal" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Select Order Head</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          @lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
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

  $('.vview').click(function(){
    // console.log($(this).data("id"));
    var id = $(this).attr("data-id");
    $('#myModal2 .modal-body').load('{{ url("admin/work_order/view_event")}}/'+id,function(result){
      $('#myModal2').modal({show:true});
    });
  });
  $('.vorderhead').click(function(){
    // console.log($(this).data("id"));
    var id = $(this).attr("data-id");
    $('#orderHeadModal .modal-body').load('{{ url("admin/work_order/add_order_head")}}/'+id,function(result){
      $('#orderHeadModal').modal({show:true});
    });
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
</script>
@endsection
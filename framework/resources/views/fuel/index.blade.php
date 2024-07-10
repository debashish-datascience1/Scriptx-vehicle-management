@extends("layouts.app")
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item active"> @lang('fleet.fuel')</li>
@endsection
@section('extra_css')
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
  .pagination{float: right;}
</style>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          @lang('fleet.manageFuel')
          &nbsp;
          <a href="{{ route('fuel.create')}}" class="btn btn-success">@lang('fleet.addNew')</a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="data_table12">
            <thead class="thead-inverse">
              <tr>
                <th>
                  @if($data->count() > 0)
                  {{-- <input type="checkbox" id="chk_all"> --}}
                  @endif
                </th>
                <th>SL#</th>
                <th>Vehicle</th>
                <th>@lang('fleet.date')</th>
                <th>@lang('fleet.fuelType')</th>
                <th>@lang('fleet.qty')</th>
                <th>@lang('fleet.cost')</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>Total</th>
                {{-- <th>@lang('fleet.consumption')</th> --}}
                <th>@lang('fleet.action')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $k=>$row)
              <tr>
                <td>
                  {{-- <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'> --}}
                </td>
                <td>{{$k+$data->firstItem()}}</td>
                <td>
                  {{-- @if($row->vehicle_data['vehicle_image'] != null)
                    <img src="{{asset('uploads/'.$row->vehicle_data['vehicle_image'])}}" height="70px" width="70px">
                  @else
                    <img src="{{ asset("assets/images/vehicle.jpeg")}}" height="70px" width="70px">
                  @endif --}}
                  <strong>{{date('d-M-Y',strtotime($row->vehicle_data['year']))}}</strong><br>
                  <a href="{{ url("admin/vehicles/".$row->vehicle_id."/edit")}}" target="_blank">
                  {{-- <span class="badge badge-success"></span> --}}
                  {{$row->vehicle_data['make']}}-{{$row->vehicle_data['model']}}
                  </a>
                  <br>
                  @if($row->vehicle_data['vehicle_image'] != null)
                    <a href="{{asset('uploads/'.$row->vehicle_data['vehicle_image'])}}" target="_blank" class="badge badge-danger">{{strtoupper($row->vehicle_data['license_plate'])}}</a>
                  @else
                    <a href="#" target="_blank" class="badge badge-danger">{{strtoupper($row->vehicle_data['license_plate'])}}</a>
                  @endif
                  <br>
                </td>
                <td>
                  <strong>{{!empty(Helper::getTransaction($row->id,20)) ? Helper::getTransaction($row->id,20)->transaction_id : ''}}</strong>
                  <br>
                  {{Helper::getCanonicalDate($row->date,'default')}}
                  <br>
                  {{$row->province}}
                </td>
                <td>
                  @if($row->fuel_details!='')
                    {{$row->fuel_details->fuel_name}}
                  @else
                    <small style="color:red">specify fuel type</small>
                  @endif
                </td>
                <td> {{$row->qty}} @if(Hyvikk::get('fuel_unit') == "gallon") @lang('fleet.gal') @else Liter @endif </td>
                <td>
                  @php ($total = $row->qty * $row->cost_per_unit)
                  {{ Hyvikk::get('currency') }} {{$total}}
                  <br>
                  {{ Hyvikk::get('currency') }} {{$row->cost_per_unit}}/ {{ Hyvikk::get('fuel_unit') }}
                </td>
                <td>
                  @if(!empty($row->cgst))
                  {{$row->cgst}} %
                  <br>
                  {{ Hyvikk::get('currency') }} {{$row->cgst_amt}}
                  @endif
                </td>
                <td>
                  @if(!empty($row->sgst))
                  {{$row->sgst}} %
                  <br>
                  {{ Hyvikk::get('currency') }} {{$row->sgst_amt}}
                  @endif
                </td>
                <td>
                  @if(!empty($row->grand_total))
                  {{ Hyvikk::get('currency') }} {{$row->grand_total}}
                  @endif
                </td>
                {{-- <td>
                  @lang('fleet.start'): {{$row->start_meter}} {{Hyvikk::get('dis_format')}}
                  <br>
                  @lang('fleet.end'): {{$row->end_meter}} {{Hyvikk::get('dis_format')}}
                  <br>
                  @lang('fleet.distence'):

                  @if($row->end_meter == 0)
                  0.00 {{Hyvikk::get('dis_format')}}
                  @else
                  {{$row->end_meter - $row->start_meter}}  {{Hyvikk::get('dis_format')}}
                  @endif
                </td> --}}
                {{-- <td>
                  {{ $row->consumption }}
                  @if(Hyvikk::get('dis_format') == "km")
                  @if(Hyvikk::get('fuel_unit') == "gallon")KMPG @else KMPL @endif
                  @else
                  @if(Hyvikk::get('fuel_unit') == "gallon")MPG @else MPL @endif
                  @endif
                </td> --}}
                <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item vview" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> @lang('fleet.view')</a>
                    @if(Helper::isEligible($row->id,20))
                    <a class="dropdown-item" href="{{ url("admin/fuel/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                    <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                    @endif
                  </div>
                </div>
                {!! Form::open(['url' => 'admin/fuel/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
                {!! Form::hidden("id",$row->id) !!}
                {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
            </tbody>
            {{-- <tfoot>
              <tr>
                <td colspan="8"></td>
                <td colspan="3">{{$data->links()}}</td>
                <td></td>
                <th>
                @if($data->count() > 0)
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button>
                @endif
                </th>
                <th>Vehicle</th>
                <th>@lang('fleet.date')</th>
                <th>@lang('fleet.fuelType')</th>
                <th>@lang('fleet.qty')</th>
                <th>@lang('fleet.cost')</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>Total</th>
                <th>@lang('fleet.meter')</th>
                <th>@lang('fleet.consumption')</th>
                <th>@lang('fleet.action')</th>
              </tr>
            </tfoot> --}}
          </table>
        </div>
        <div class="row">
          <div class="col-md-12 float-right">{{$data->links()}}</div>
        </div>
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
        {!! Form::open(['url'=>'admin/delete-fuel','method'=>'POST','id'=>'form_delete']) !!}
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
    $('#myModal2 .modal-body').load('{{ url("admin/fuel/view_event")}}/'+id,function(result){
      $('#myModal2').modal({show:true});
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

  // Data Table for this specific page
  //Because it loads so much that it causes timeout.. god damn
  // $(document).ready(function() {
  //   $('#data_tabled tfoot th').each( function () {
  //     // console.log($('#data_tabled tfoot th').length);
  //     if($(this).index() != 0 && $(this).index() != $('#data_tabled tfoot th').length - 1) {
  //       var title = $(this).text();
  //       $(this).html( '<input type="text" placeholder="'+title+'" />' );
  //     }
  //   });

  //   var table = $('#data_tabled').DataTable({
  //     "language": {
  //         "url": '{{ __("fleet.datatable_lang") }}',
  //     },
  //     columnDefs: [ { orderable: false, targets: [0] } ],
  //     // individual column search
  //     "initComplete": function() {
  //               table.columns().every(function () {
  //                 var that = this;
  //                 $('input', this.footer()).on('keyup change', function () {
  //                   // console.log($(this).parent().index());
  //                     that.search(this.value).draw();
  //                 });
  //               });
  //       },
  //       "processing": true,
  //       "serverSide": true,
  //       "ajax":'{{route("fuel.getFuelList")}}'
  //   });

  //   $('[data-toggle="tooltip"]').tooltip();

  // });
</script>
@endsection
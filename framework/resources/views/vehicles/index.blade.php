@extends("layouts.app")
@section('extra_css')
<style type="text/css">
  .modal-open {margin-left: -250px}
  .custom_padding{
    padding: .3rem !important;
  }
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.vehicles')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.manageVehicles') &nbsp; <a href="{{ route('vehicles.create')}}" class="btn btn-success">@lang('fleet.addNew')</a>
          <button data-toggle="modal" data-target="#import" class="btn btn-warning">@lang('fleet.import')</button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 25px">
          <thead class="thead-inverse">
            <tr>
              <th>
                @if($data->count() > 0)
                <input type="checkbox" id="chk_all">
                @endif
              </th>
              <th>@lang('fleet.vehicleImage')</th>
              <th>@lang('fleet.make')</th>
              <th>@lang('fleet.model')</th>
              <th>@lang('fleet.type')</th>
              <th>@lang('fleet.color')</th>
              <th>@lang('fleet.licenseNo')</th>
              {{-- <th>@lang('fleet.group')</th> --}}
              <th>@lang('fleet.service')</th>
              <th>@lang('fleet.assigned_driver')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($data as $row)
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
              </td>
              <td>
                @if($row->vehicle_image != null)
                <img src="{{asset('uploads/'.$row->vehicle_image)}}" height="70px" width="70px">
                @else
                <img src="{{ asset("assets/images/vehicle.jpeg")}}" height="70px" width="70px">
                @endif
              </td>
              <td>{{$row->make}}</td>
              <td>{{$row->model}}</td>
              <td>@if($row->type_id){{$row->types->displayname}}@endif</td>
              <td>{{$row->color}}</td>
              <td>{{$row->license_plate}}</td>
              {{-- <td>
               @if($row->group_id){{$row->group->name}}@endif
              </td> --}}
              <td>{{($row->in_service)?"YES":"NO"}}</td>
              <td>
                @if(empty($row->driver) || empty($row->driver->assigned_driver)) 
                  <span class="badge badge-danger">Driver Not Assigned</span>
                @else
                  {{$row->driver->assigned_driver->name}}
                @endif
              </td>
              <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item openBtn" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal2" id="openBtn">
                    <span class="fa fa-eye" aria-hidden="true" style="color: #398439"></span> @lang('fleet.view_vehicle')
                    </a>
                    <a class="dropdown-item" href="{{ url("admin/vehicles/".$row->id."/edit") }}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                    {!! Form::hidden("id",$row->id) !!}
                    <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                    {{-- <a class="dropdown-item" href="{{ route('vehicle_tax',$row->id) }}"> <span aria-hidden="true" class="fa fa-inr" style="color: #3c2cd4;"></span> @lang('fleet.vehicle_tax')</a> --}}
                  </div>
                </div>
                {!! Form::open(['url' => 'admin/vehicles/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}

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
              <th>@lang('fleet.vehicleImage')</th>
              <th>@lang('fleet.make')</th>
              <th>@lang('fleet.model')</th>
              <th>@lang('fleet.type')</th>
              <th>@lang('fleet.color')</th>
              <th>@lang('fleet.licenseNo')</th>
              {{-- <th>@lang('fleet.group')</th> --}}
              <th>@lang('fleet.service')</th>
              <th>@lang('fleet.assigned_driver')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="import" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.importVehicles')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/import-vehicles','method'=>'POST','files'=>true]) !!}
        <div class="form-group">
          {!! Form::label('excel',__('fleet.importVehicles'),['class'=>"form-label"]) !!}
          {!! Form::file('excel',['class'=>"form-control",'required']) !!}
        </div>
        <div class="form-group">
          <a href="{{ asset('assets/samples/vehicles.xlsx') }}">@lang('fleet.downloadSampleExcel')</a>
        </div>
        <div class="form-group">
          <h6 class="text-muted">@lang('fleet.note'):</h6>
          <ul class="text-muted">
            <li>@lang('fleet.vehicleImportNote1')</li>
            <li>@lang('fleet.vehicleImportNote2')</li>
            <li>@lang('fleet.excelNote')</li>
            <li>@lang('fleet.fileTypeNote')</li>
          </ul>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" type="submit">@lang('fleet.import')</button>
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
        {!! Form::open(['url'=>'admin/delete-vehicles','method'=>'POST','id'=>'form_delete']) !!}
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
  <div class="modal-dialog" role="document">
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

<!--model 2 -->
<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 104%">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.vehicle')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          @lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<!--model 2 -->
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

  $('.openBtn').click(function(){
    // alert($(this).data("id"));
    var id = $(this).attr("data-id");
    // alert('{{ url("admin/vehicle/event")}}/'+id)
    $('#myModal2 .modal-body').load('{{ url("admin/vehicle/event")}}/'+id,function(result){
      // console.log(result);
      $('#myModal2').modal({show:true});
    });
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
</script>
@endsection
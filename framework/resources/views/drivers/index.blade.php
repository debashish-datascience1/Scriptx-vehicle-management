@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<style type="text/css">
  .mybtn1
  {
   padding-top: 4px;
    padding-right: 8px;
    padding-bottom: 4px;
    padding-left: 8px;
  }

  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.drivers')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('menu.drivers') &nbsp;
          <a href="{{ route("drivers.create") }}" class="btn btn-success"> @lang('fleet.addDriver') </a>
          <button data-toggle="modal" data-target="#import" class="btn btn-warning">@lang('fleet.import')</button>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                @if($data->count() > 0)
                <input type="checkbox" id="chk_all">
                @endif
              </th>
              <th>#</th>
              <th>@lang('fleet.driverImage')</th>
              <th>@lang('fleet.name')</th>
              {{-- <th>@lang('fleet.email')</th> --}}
              <th>@lang('fleet.is_active')</th>
              <th>@lang('fleet.phone')</th>
              <th>@lang('fleet.assigned_vehicle')</th>
              <th>@lang('fleet.start_date')</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $row)
            <tr>
              <td>
                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'>
              </td>
              <td>{{$row->id}}</td>
              <td>
                @if($row->getMeta('driver_image') != null)
                @if(starts_with($row->getMeta('driver_image'),'http'))
                @php($src = $row->getMeta('driver_image'))
                @else
                @php($src=asset('uploads/'.$row->getMeta('driver_image')))
                @endif
                <img src="{{$src}}" height="70px" width="70px">
                @else
                <img src="{{ asset("assets/images/no-user.jpg")}}" height="70px" width="70px">
                @endif
              </td>
              <td>{{$row->name}}</td>
              {{-- <td>{{$row->email}}</td> --}}
              <td>
                @if($row->getMeta('is_active'))
                  <a href="{{ url("admin/drivers/disable/".$row->id)}}" class="badge badge-success">YES</a>
                @else
                  <a href="{{ url("admin/drivers/enable/".$row->id)}}" class="badge badge-danger">NO</a>
                @endif
              </td>
              <td>{{$row->getMeta('phone')}}</td>
              <td>@if($row->vehicle_id != null && $row->getMeta('is_active')==1 && !empty($row->driver_vehicle->vehicle)) 
              {{$row->driver_vehicle->vehicle->make}}-{{$row->driver_vehicle->vehicle->model}}-{{$row->driver_vehicle->vehicle->license_plate}} 
              @endif
              </td>
              <td>{{ empty($row->getMeta('start_date')) ? Helper::getCanonicalDate($row->created_at,'default') : Helper::getCanonicalDate($row->getMeta('start_date'),'default')}}</td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="View Driver Details"><i class="fa fa-eye" aria-hidden="true" style="color:#398439;"></i>View Driver Details </a>
                  {{-- <a class="dropdown-item" class="mybtn changepass" data-id="{{$row->id}}" data-toggle="modal" data-target="#changepass" title="@lang('fleet.change_password')"><i class="fa fa-key" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.change_password')</a> --}}
                  <a class="dropdown-item" href="{{ url("admin/drivers/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>
                  @if($row->getMeta('is_active'))
                  <a class="dropdown-item" href="{{ url("admin/drivers/disable/".$row->id)}}" class="mybtn" data-toggle="tooltip"  title="@lang('fleet.disable_driver')"><span class="fa fa-times" aria-hidden="true" style="color: #5cb85c;"></span> @lang('fleet.disable_driver')</a>
                  @else
                  <a class="dropdown-item" href="{{ url("admin/drivers/enable/".$row->id)}}" class="mybtn" data-toggle="tooltip"  title="@lang('fleet.enable_driver')"><span class="fa fa-check" aria-hidden="true" style="color: #5cb85c;"></span> @lang('fleet.enable_driver')</a>
                  @endif
                </div>
              </div>
              {!! Form::open(['url' => 'admin/drivers/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
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
              <th>#</th>
              <th>@lang('fleet.driverImage')</th>
              <th>@lang('fleet.name')</th>
              {{-- <th>@lang('fleet.email')</th> --}}
              <th>@lang('fleet.is_active')</th>
              <th>@lang('fleet.phone')</th>
              <th>@lang('fleet.assigned_vehicle')</th>
              <th>@lang('fleet.start_date')</th>
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
        <h4 class="modal-title">@lang('fleet.importDrivers')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/import-drivers','method'=>'POST','files'=>true]) !!}
        <div class="form-group">
          {!! Form::label('excel',__('fleet.importDrivers'),['class'=>"form-label"]) !!}
          {!! Form::file('excel',['class'=>"form-control",'required']) !!}
        </div>
        <div class="form-group">
          <a href="{{ asset('assets/samples/drivers.xlsx') }}">@lang('fleet.downloadSampleExcel')</a>
        </div>
        <div class="form-group">
          <h6 class="text-muted">@lang('fleet.note'):</h6>
          <ul class="text-muted">
            <li>@lang('fleet.driverImportNote1')</li>
            <li>@lang('fleet.driverImportNote2')</li>
            <li>@lang('fleet.driverImportNote3')</li>
            <li>@lang('fleet.excelNote')</li>
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
        {!! Form::open(['url'=>'admin/delete-drivers','method'=>'POST','id'=>'form_delete']) !!}
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
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Driver Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div id="changepass" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.change_password')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>url('admin/change_password'),'id'=>'changepass_form']) !!}
        <form id="change" action="{{url('admin/change_password')}}" method="POST">
      {!! Form::hidden('driver_id',"",['id'=>'driver_id'])!!}
       <div class="form-group">
        {!! Form::label('passwd',__('fleet.password'),['class'=>"form-label"]) !!}
        <div class="input-group mb-3">
         <div class="input-group-prepend">
          <span class="input-group-text"><i class="fa fa-lock"></i></span></div>
        {!! Form::password('passwd',['class'=>"form-control",'id'=>'passwd','required']) !!}
        </div>
      </div>
      <div class="modal-footer">
        <button id="password" class="btn btn-info" type="submit" >@lang('fleet.change_password')</button>
      </form>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
@endsection

@section('script')
<script type="text/javascript">

  $(function(){
    $(".vevent").on("click",function(){
      var id = $(this).data("id");
      $("#viewModal .modal-body").load('{{url("admin/drivers/event")}}/'+id,function(result){
        $("#viewModal").modal({show:true})
      })
    })
  })

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('#changepass').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#driver_id").val(id);
  });

  $("#changepass_form").on("submit",function(e){
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
       new PNotify({
            title: 'Success!',
            text: "@lang('fleet.passwordChanged')",
            type: 'info'
        });
      },
      dataType: "html"
    });
    $('#changepass').modal("hide");
    e.preventDefault();
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
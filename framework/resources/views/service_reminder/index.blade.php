@extends("layouts.app")
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.serviceReminders')</li>
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
        @lang('fleet.serviceReminders')
        &nbsp;
        <a href="{{ route('service-reminder.create')}}" class="btn btn-success" style="margin-bottom: 5px">@lang('fleet.add_service_reminder')</a> &nbsp;
        <a href="{{ route('service-item.create')}}" class="btn btn-success" style="margin-bottom: 5px">@lang('fleet.add_service_item')</a></h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              {{-- <th>
                @if($service_reminder->count() > 0)
                  <input type="checkbox" id="chk_all">
                @endif
              </th> --}}
              <th>SL#</th>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.service_item')</th>
              <th>@lang('fleet.start_date') / @lang('fleet.last_performed') </th>
              <th>@lang('fleet.next_due') (@lang('fleet.date'))</th>
              <th>@lang('fleet.next_due') (@lang('fleet.meter'))</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($service_reminder as $k=>$reminder)
            <tr>
              <td>{{$k+1}}</td>
              <td>
                  @if($reminder->vehicle['vehicle_image'] != null)
                    <img src="{{asset('uploads/'.$reminder->vehicle['vehicle_image'])}}" height="70px" width="70px">
                  @else
                    <img src="{{ asset("assets/images/vehicle.jpeg")}}" height="70px" width="70px">
                  @endif
              </td>
              <td>
                <strong>{{$reminder->vehicle->license_plate}}</strong>
              </td>
              <td>
                {{$reminder->services['description']}}
                <br>
                @lang('fleet.interval'): {{$reminder->services->overdue_time}} {{$reminder->services->overdue_unit}}
                @if($reminder->services->overdue_meter != null)
                @lang('fleet.or') {{$reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
                @endif
              </td>
              <td>
                @lang('fleet.start_date'): {{date($date_format_setting,strtotime($reminder->last_date))}}
                <br>
                @lang('fleet.last_performed') @lang('fleet.meter'): {{$reminder->last_meter}}
              </td>
              <td>
                @php($interval = substr($reminder->services->overdue_unit,0,-3))
                @if($reminder->services->overdue_time != null)
                  @php($int = $reminder->services->overdue_time.$interval)
                @else
                  @php($int = Hyvikk::get('time_interval')."day")
                @endif
                  
                @if($reminder->last_date != 'N/D')
                 @php($date = date('Y-m-d', strtotime($int, strtotime($reminder->last_date)))) 
                @else
                 @php($date = date('Y-m-d', strtotime($int, strtotime(date('Y-m-d'))))) 
                @endif
                {{-- {{dd($date)}} --}}
                {{ date($date_format_setting,strtotime($date)) }}
                <br>
                @php   ($to = \Carbon\Carbon::now())

                @php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $date))

                @php ($diff_in_days = $to->diffInDays($from))
                @lang('fleet.after') {{$diff_in_days}} @lang('fleet.days')
              </td>
              <td>
                @if($reminder->services->overdue_meter != null)
                  @if($reminder->last_meter == 0)
                    {{$reminder->vehicle->int_mileage + $reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
                  @else
                    {{$reminder->last_meter + $reminder->services->overdue_meter}} {{Hyvikk::get('dis_format')}}
                  @endif
                @endif
              </td>
              <td>
                {!! Form::open(['url' => 'admin/service-reminder/'.$reminder->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$reminder->id]) !!}

                {!! Form::hidden("id",$reminder->id) !!}
                  <button type="button" class="btn btn-danger" data-id="{{$reminder->id}}" data-toggle="modal" data-target="#myModal" title="@lang('fleet.delete')">
                  <span class="fa fa-times" aria-hidden="true"></span>
                  </button>
                {!! Form::close() !!}
              </td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>
                @if($service_reminder->count() > 0)
                  <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled>@lang('fleet.delete')</button>
                @endif
              </th>
              <th></th>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.service_item')</th>
              <th>@lang('fleet.start_date') / @lang('fleet.last_performed')</th>
              <th>@lang('fleet.next_due') (@lang('fleet.date'))</th>
              <th>@lang('fleet.next_due') (@lang('fleet.meter'))</th>
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
        {!! Form::open(['url'=>'admin/delete-reminders','method'=>'POST','id'=>'form_delete']) !!}
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
</script>
@endsection
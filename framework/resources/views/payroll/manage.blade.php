@extends("layouts.app")
@section("breadcrumb")
<li class="breadcrumb-item active">Manage Payroll</li>
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
        <h3 class="card-title">
        @lang('fleet.payroll')
        &nbsp;
        <a href="{{ route('payroll.index')}}" class="btn btn-success">@lang('fleet.addPayroll')</a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_table">
          <thead class="thead-inverse">
            <tr>
              <th>SL#</th>
              <th>Driver</th>
              <th>Vehicle</th>
              <th>For Month</th>
              <th>Present/Absent</th>
              <th>Salary</th>
              <th>Total Payable Salary</th>
              <th>Paid Amount</th>
              <th>Booking Advance</th>
              <th>Salary Advance</th>
              <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($payrolls as $k=>$row)
            <tr>
              <td>{{$k+1}}</td>
              <td>
                @if(!empty($row->driver))
                  {{$row->driver->name}}
                @else
                  <span class="badge badge-primary">No Driver Assigned</span>
                @endif
              </td>
              <td>
                @if(!empty($row->driver_vehicle))
                  <strong>{{$row->driver_vehicle->vehicle->license_plate}}</strong>
                @else
                  <span class="badge badge-danger">No Vehicle Assigned</span>
                @endif
              </td>
              <td>
                @php $month = $row->for_month<10 ? "0".$row->for_month:$row->for_month;  @endphp
                {{date("m-Y",strtotime($row->for_year."-".$month."-01"))}}
              </td>
              <td>{{$row->working_days}}/{{!empty($row->absent_days) ? $row->absent_days : (cal_days_in_month(CAL_GREGORIAN, $row->for_month, $row->for_year)-$row->working_days)}}</td>
              <td>{{bcdiv($row->salary,1,2)}}</td>
              <td>{{bcdiv($row->total_payable_salary,1,2)}}</td>
              <td>{{bcdiv($row->payable_salary,1,2)}}</td>
              <td>{{bcdiv($row->advance_driver,1,2)}}</td>
              <td>{{bcdiv($row->advance_salary,1,2)}}</td>
              <td>
              <div class="btn-group">
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu custom" role="menu">
                  <a class="dropdown-item mybtn vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                  {{-- <a class="dropdown-item" href="{{url("admin/payroll/".$row->id."/edit") }}"><span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  {!! Form::hidden("id",$row->id) !!}
                  <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a> --}}
                </div>
              </div>
                {!! Form::open(['url' => 'admin/vendors/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]) !!}
                {!! Form::hidden("id",$row->id) !!}
                {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
                <th>SL#</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>For Month</th>
                <th>Present/Absent</th>
                <th>Salary</th>
                <th>Total Payable Salary</th>
                <th>Paid Amount</th>
                <th>Booking Advance</th>
                <th>Salary Advance</th>
                <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->

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
        {!! Form::open(['url'=>'admin/delete-vendors','method'=>'POST','id'=>'form_delete']) !!}
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
<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="width: 200%;margin-left: -150px;">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.view')</h4>
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
@endsection

@section('script')
<script type="text/javascript">
 $(".vevent").click(function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('{{url("admin/payroll/view_event")}}/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
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
  $(function(){
      $(document).on('click','.advbook',function(){
          $("#viewModal .modal-content").css('width','120%');
      })
      $(document).on('click','.advgeneral',function(){
          $("#viewModal .modal-content").css('width','205%');
      })
  })
</script>
@endsection
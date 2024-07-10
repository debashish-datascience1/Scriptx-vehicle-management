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
  /* .viewModal{color:#fff} */

  .nav-tabs-custom>.nav-tabs>li.active{border-top-color:#00a65a !important;}

/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
.custom .nav-link.active {

    background-color: #21bc6c !important;

}
</style>

@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('fleet.payroll')</li>
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
        <h3 class="card-title">@lang('menu.payroll') &nbsp;
          {{-- <a href="{{ route("payroll.create") }}" class="btn btn-success"> @lang('fleet.addPayroll') </a> --}}
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
              <th>Driver</th>
              <th>Vehicle</th>
              <th>Salary</th>
              {{-- <th>@lang('fleet.email')</th> --}}
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
                {{$row->name}}
              </td>
              {{-- <td>
                {{$row->driver_vehicle->vehicle->make}}-{{$row->driver_vehicle->vehicle->model}}-{{$row->driver_vehicle->vehicle->license_plate}}
              </td> --}}
              <td>
                @if(!empty($row->driver_vehicle))
                  {{$row->driver_vehicle->vehicle->make}}-{{$row->driver_vehicle->vehicle->model}}-{{$row->driver_vehicle->vehicle->license_plate}}
                @else
                  <span class="badge badge-danger">Driver Not Assigned</span>
                @endif

              </td>
              <td><span class="fa fa-inr"> {{number_format($row->salary)}}</span></td>
              <td>
              <div class="btn-group">
                <a class="vpay btn btn-success" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')" style="color: #fff">Pay</a>
                {{-- <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                  <span class="fa fa-gear"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button> --}}
                {{-- <div class="dropdown-menu custom" role="menu"> --}}
                  {{-- <a class="dropdown-item" class="mybtn viewModal" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                  <a class="dropdown-item" href="{{ url("admin/drivers/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                  <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a> --}}
                {{-- </div> --}}
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
              <th>@lang('fleet.name')</th>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.salary')</th>
              {{-- <th>@lang('fleet.email')</th> --}}
              {{-- <th>@lang('fleet.forMonth')</th>
              <th>@lang('fleet.date')</th> --}}
              {{-- <th>@lang('fleet.assigned_vehicle')</th>
              <th>@lang('fleet.start_date')</th> --}}
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
        {{-- {!! Form::open(['url'=>'','method'=>'POST','id'=>'form_delete']) !!}
        <div id="bulk_hidden"></div>
        <p>@lang('fleet.confirm_bulk_delete')</p> --}}
      </div>
      <div class="modal-footer">
        {{-- <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">@lang('fleet.delete')</button> --}}
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
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width:50%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Pay</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
        </button>
      </div> --}}
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">

  $(".vpay").click(function(){
      var id = $(this).data('id');
      $("#viewModal .modal-body").load('{{url("admin/payroll/single_pay")}}/'+id,function(result){
         $("#viewModal").modal({show:true})
      })
  })

  $('body').on('click','#payroll',function(e){
    var blankTest = /\S/;
    var remain = atob($("#remaining").val());
    var paysal = $("#payable_salary").val();
    var dump = {_token:'{{csrf_token()}}',remain:remain,paysal:paysal};
    if(!blankTest.test(paysal)){
      alert("Payable Salary Cannot be empty. Select month, year and try again..")
      return false;
    }else{
      // console.log(remain);
      // console.log(paysal);
      // console.log(dump);
      if(confirm('Are you sure ?')){
        var posting = $.post('{{url("admin/payroll/purse")}}',dump);
        posting.done(function(data){
          // console.log(data)
          if(data.can==false)
            return false;
        })
      }
      // console.log(data);
      // return false;
    }

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
    console.log($( "input[name='ids[]']:checked" ).length);
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
  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }

  $(document).on('change','#month,#year',function(){
    $("#working_days").val('');
    $("#absent_days").val('');
    $("#advance_salary").val('');
    $("#advance_driver").val('');
    $("#total_payable_salary").val('');
    $("#payable_salary").val('');
    $("#carried_salary").val('');
  })

  $(document).on('change keyup','#month,#year,#working_days',function(){
    var month = $("#month").val();
    var year = $("#year").val();
    var working_days = $("#working_days").val();
    var total_days = $("#working_days").data("totaldays");
    var driver_id = $("#driver_id").val();
    if(month=='' || month==null){
      alert('Please select Month');
      return false;
    }

    if(year=='' || year==null){
      alert('Please select Year');
      return false;
    }

    if(working_days>total_days && $("#manual").is(":checked")){
      alert('Working days cannot be more than number of days in this month');
      $("#working_days").val(0);
      working_days = 0;
      return false;
    }

    var data = {_token:"{{csrf_token()}}",month:month,year:year,driver_id:driver_id,working_days:working_days};
    var posting = $.post('{{url("admin/payroll/getWorkingDays")}}',data);
    posting.done(function(resp){
      console.log(resp);
      $("#working_days").val(resp.presentDays);
      $("#working_days").attr("data-totaldays",resp.totalMonthDays);
      // $("#working_days").data("totaldays",resp.totalMonthDays);
      $("#absent_days").val(resp.absentDays);
      $("#advance_salary").val(resp.salary_advance);
      $("#advance_driver").val(resp.bookingAdvance);
      $(".expenditure_div").html(resp.view);
      $("#deduct_sal").html("Deduct Salary <span class='fa fa-inr'></span>"+resp.deduct_amount);
      $("#deduct_salary").val(resp.deduct_amount);
      $("#payable_sal").html("Payable Salary <span class='fa fa-inr'></span>"+resp.payable_salary);
      $("#advancedriver_sal").html("Advance to Driver <span class='fa fa-inr'></span>"+resp.bookingAdvance);
      $("#payable_salary").val(resp.payable_salary);
      $("#total_payable_salary").val(resp.total_payable_salary);
      $("#carried_salary").val(resp.carried_salary);
      $("#payable_actual").val(resp.payable_salary);
      // if(resp.isLeaveChecked==true){
      //   // $(".manual_workingdays").hide();
      //   $("#working_days").prop('readonly',true);
      // }else{
      //   // $(".manual_workingdays").show();
      //   $("#working_days").prop('readonly',false);
      //   $("#absent_days").val($("#working_days").data("totaldays"));
      //   // $("#working_days").trigger('keyup');
      // }
      console.log(resp.isLeaveChecked,resp.yetToComplete,resp.isAlreayPaid)
      if(resp.isLeaveChecked && resp.yetToComplete==null && resp.isAlreayPaid==false){
        console.log('satisfies')
        $("#working_days").prop('readonly',true);
        $("#payable_salary").prop('readonly',false);
        $("#payroll").prop("disabled",false);
      }else{
        $("#working_days").prop('readonly',false);
        $("#payable_salary").prop('readonly',true);
        $("#payroll").prop("disabled",true);
      }
    })
  })

  $(document).on('keyup','#payable_salary',function(){
    var total = $("#total_payable_salary").val();
    var payable = $("#payable_salary").val();
    var data = {_token:"{{csrf_token()}}",total:total,payable:payable}
    $.post("{{route('payroll.payabletype')}}",data).done(function(result){
      if(result.ismore)
        $("#payable_salary").val('');
    });
  })


  
  

  

  // $(document).on('click','#manual',function(){
  //   if($("#manual").is(":checked"))
  //     $("#working_days").prop('readonly',false);
  //   else
  //     $("#working_days").prop('readonly',true);
  // })
  
</script>
@endsection
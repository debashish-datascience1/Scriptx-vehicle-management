@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
  <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
  {{-- <style type="text/css">
    .checkbox, #chk_all{
      width: 20px;
      height: 20px;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
  </style> --}}
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">Vehicle Documents</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header with-border">
        <h3 class="card-title"> Manage Vehicle Documents &nbsp;
          <a href="{{route("vehicle-docs.create")}}" class="btn btn-success">Renew Vehicle Documents</a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-responsive display" id="data_table1" style="padding-bottom: 35px; width: 100%">
            <thead class="thead-inverse">
              <tr>
                <th>
                  {{-- @if($data->count() > 0)
                  <input type="checkbox" id="chk_all">
                  @endif --}}
                </th>
                <th>Vehicle</th> {{--transaction_id,vehicle,driver_id--}}
                <th>Date</th> {{--date,till--}}
                <th>Document</th> {{--param_id,bank_id--}}
                <th>Status</th>
                <th>@lang('fleet.action')</th>
              </tr>
            </thead>
            <tbody>
              @foreach($docs as $row)
               <tr>
                <td>
                  {{-- <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="checkbox" id="chk{{ $row->id }}" onclick='checkcheckbox();'> --}}
                </td>
                <td>
                  {{$row->transaction->transaction_id}}
                  <br>
                  {{$row->vehicle->make}} - {{$row->vehicle->model}} - <label>{{$row->vehicle->license_plate}}</label>
                  <br>
                  @if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver))
                    {{$row->drivervehicle->assigned_driver}}
                  @endif
                </td>
                <td>
                  <label>On : {{$row->date}}</label><br>
                  <label>Till : {{$row->till}}</label>
                </td>
                <td>
                  <label>{{$row->document->label}}</label>
                  <br>
                  {{$row->transaction->bank->bank}}
                </td>
                <td>
                  @if($row->status==1)
                  <span class="text-success">Completed</span>
                  @else
                  <span class="text-warning">In Progress</span>
                  @endif
                </td>
                <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    
                  </div>
                </div>
                {!! Form::open(['url' => 'admin/bookings/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]) !!}
                {!! Form::hidden("id",$row->id) !!}
                {!! Form::close() !!}
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th>Vehicle</th> {{--transaction_id,vehicle,driver_id--}}
                <th>Date</th> {{--date,till--}}
                <th>Document</th> {{--param_id,bank_id--}}
                <th>Status</th>
                <th>@lang('fleet.action')</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>



<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Document Renew Details</h4>
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

@endsection

@section("script")

<script src="{{ asset('assets/js/moment.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">



      $('.date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      });



  $('.vbook').click(function(){
    // alert($(this).data("id"));
    var id = $(this).attr("data-id");
    // alert('{{ url("admin/vehicle/event")}}/'+id)
    $('#myModal2 .modal-body').load('{{ url("admin/bookings/event")}}/'+id,function(result){
      // console.log(result);
      $('#myModal2').modal({show:true});
      if($('.adexist').length) $("#myModal2 .modal-content").css('width','111%');
      else $("#myModal2 .modal-content").css('width','100%');
    });
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

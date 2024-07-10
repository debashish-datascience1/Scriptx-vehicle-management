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
<li class="breadcrumb-item active">Vehicle Documents</li>
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
        <h3 class="card-title"> Manage Vehicle Documents &nbsp;
          <a href="{{route("vehicle-docs.create")}}" class="btn btn-success">Renew Vehicle Documents</a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="myTable" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
                <th>SL#</th>
                <th>Vehicle</th> {{--transaction_id,vehicle,driver_id--}}
                <th>Date</th> {{--date,till--}}
                <th>Document / Bank</th> {{--param_id,bank_id--}}
                <th>Amount / Vendor</th> 
                <th>Method/Reference</th>
                <th>@lang('fleet.action')</th>
            </tr>
          </thead>
          <tbody>
            @foreach($docs as $k=>$row)
            <tr>
                <td width="5%">{{$k+1}}</td>
                <td>
                  {{-- {{empty($row->transaction) ? dd($row->id) : '-'}} --}}
                  <b><i>{{$row->transaction->transaction_id}}</i></b>
                  <br>
                  {{$row->vehicle->make}} - {{$row->vehicle->model}} - <label>{{$row->vehicle->license_plate}}</label>
                  <br>
                  @if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver))
                    {{$row->drivervehicle->assigned_driver->name}}
                  @endif
                </td>
                <td>
                  <label>On : {{Helper::getCanonicalDate($row->date,'default')}}</label><br>
                  <label>Till : {{Helper::getCanonicalDate($row->till,'default')}}</label><br>
                  @php   ($to = \Carbon\Carbon::now())

                  @php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $row->till))
  
                  @php ($diff_in_days = $to->diffInDays($from))
                  <label>@lang('fleet.after') {{$diff_in_days}} @lang('fleet.days')</label>
                </td>
                <td>
                  <label>{{$row->document->label}}</label>
                  <br>
                  {{$row->transaction->bank->bank}}
                  <br>
                  {{$row->transaction->bank->account_no}}
                </td>
                <td>
                    {{Hyvikk::get('currency')}}{{Helper::properDecimals($row->amount)}}
                    <br>
                    {{$row->vendor->name}}
                </td>
                <td>
                  {{$row->method_param->label}} <br>
                  {{$row->ddno}}
                </td>
                {{-- <td>
                  @if($row->status==1)
                  <span class="text-success">Completed</span>
                  @else
                  <span class="text-warning">In Progress</span>
                  @endif
                </td> --}}
                <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item mybtn vevent" data-id="{{$row->id}}" data-toggle="modal" data-target="#viewModal" title="@lang('fleet.view')"><i class="fa fa-eye" aria-hidden="true" style="color:#269abc;"></i> @lang('fleet.view')</a>
                    {{-- <a class="dropdown-item" href="{{ url("admin/leave/".$row->id."/edit")}}"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')</a>
                    <a class="dropdown-item" data-id="{{$row->id}}" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> @lang('fleet.delete')</a>  --}}
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
                <th>SL#</th>
                <th>Vehicle</th> {{--transaction_id,vehicle,driver_id--}}
                <th>Date</th> {{--date,till--}}
                <th>Document</th> {{--param_id,bank_id--}}
                <th>Amount</th>
                <th>Status</th>
                <th>@lang('fleet.action')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
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
      $("#viewModal .modal-body").load('{{url("admin/vehicle-docs/view_event")}}/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
      })
  })



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
  
  $(function(){
    $('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
      // dom: 'Bfrtip',
      buttons: [{
           extend: 'collection',
              text: 'Export',
              buttons: [
                  'copy',
                  'excel',
                  'csv',
                  'pdf',
              ]}
      ],
      "language": {
               "url": '{{ __("fleet.datatable_lang") }}',
            },
      "initComplete": function() {
              myTable.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    that.search(this.value).draw();
                });
              });
            }

    });
  })

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
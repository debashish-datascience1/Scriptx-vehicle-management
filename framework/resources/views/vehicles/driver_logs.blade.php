@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')

@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("vehicles.index")}}">@lang('fleet.vehicles')</a></li>
<li class="breadcrumb-item active">@lang('fleet.driver_logs')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.driver_logs')</h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="driver_logs">
          <thead class="thead-inverse">
            <tr>
              <th>#</th>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.driver')</th>
              <th>@lang('fleet.assigned_on')</th>
            </tr>
          </thead>
          <tbody>
          @foreach($logs as $row)
            <tr>
              <td>{{$row->id}}</td>
              <td>{{$row->vehicle->make}} - {{$row->vehicle->model}} - {{$row->vehicle->license_plate}}</td>
              <td>{{$row->driver->name}}</td>
              <td>{{date($date_format_setting.' g:i A',strtotime($row->date))}}</td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>@lang('fleet.vehicle')</th>
              <th>@lang('fleet.driver')</th>
              <th>@lang('fleet.assigned_on')</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
  $('#driver_logs tfoot th').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
  });

  var table = $('#driver_logs').DataTable({
    "language": {
        "url": '{{ __("fleet.datatable_lang") }}',
     },
     columnDefs: [ { orderable: false, targets: [0] } ],
     // individual column search
     "initComplete": function() {
              table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                  // console.log($(this).parent().index());
                    that.search(this.value).draw();
                });
              });
            }
  });
});
</script>
@endsection
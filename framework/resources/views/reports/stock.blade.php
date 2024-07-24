@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')@endphp
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style>
    .fullsize{width: 100% !important;}
    .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
    .dateShow{padding-right: 13px;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="#">@lang('menu.reports')</a></li>
<li class="breadcrumb-item active">@lang('fleet.booking_report')</li>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">@lang('fleet.stock_report')</h3>
            </div>

            <div class="card-body">
                {!! Form::open(['route' => 'reports.stock','method'=>'post','class'=>'form-block']) !!}
                <div class="row newrow">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']) !!}
                            {!! Form::select('parts_id[]',$options,'all',['class'=>'form-control parts_id','id'=>'parts_id','multiple'=>'multiple','required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                {!! Form::text('date1', isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>'From Date','readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
                            <div class="input-group">
                              <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                              {!! Form::text('date2', isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>'To Date','readonly']) !!}
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="row newrow">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
                        <button type="submit" formaction="{{url('admin/print-stock-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@if(isset($parts))
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    Parts Stock Report
                </h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Part Name</th>
                            <th>Category</th>
                            <th>Manufacturer</th>
                            <th>Stock</th>
                            <th>Tyres Used</th>
                            <th>Tyres InStock</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($parts as $k=>$part)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$part->item ?? 'N/A'}}</td>
                            <td>{{$part->category->name ?? 'N/A'}}</td>
                            <td>{{$part->manufacturer_details->name ?? 'N/A'}}</td>
                            <td>{{$part->stock ?? 'N/A'}}</td>
                            <td>{{$tyres_used[$part->id]->total_used ?? 0}}</td>
                            <!-- <td>
								@php
									$tyre_numbers = $part->tyres_used ?? '';
									if (!empty($tyre_numbers)) {
										$numbers_array = explode(',', $tyre_numbers);
										$display_numbers = array_slice($numbers_array, 0, 2);
										$output = implode(', ', $display_numbers);
										if (count($numbers_array) > 2) {
											$output .= ', ...';
										}
									} else {
										$output = 'N/A';
									}
								@endphp
								{{ $output }}
								@if (count($numbers_array ?? []) > 2)
									<button class="btn btn-sm btn-info show-tyres" data-part-id="{{ $part->id }}" data-part-name="{{ $part->item }}">Show</button>
								@endif
							</td> -->
							<td>
								@php
									$tyre_numbers = $part->tyres_used ?? '';
									if (!empty($tyre_numbers)) {
										$numbers_array = explode(',', $tyre_numbers);
										$display_numbers = array_slice($numbers_array, 0, 2);
										$output = implode(', ', $display_numbers);
										if (count($numbers_array) > 2) {
											$output .= ', ...';
										}
									} else {
										$output = 'N/A';
										$numbers_array = [];
									}
								@endphp
								{{ $output }}
								@if (!empty($tyre_numbers) && count($numbers_array) > 2)
									<button class="btn btn-sm btn-info show-tyres" data-part-id="{{ $part->id }}" data-part-name="{{ $part->item }}">Show</button>
								@endif
							</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>SL#</th>
                            <th>Part Name</th>
                            <th>Category</th>
                            <th>Manufacturer</th>
                            <th>Stock</th>
                            <th>Tyres Used</th>
                            <th>Tyre Numbers</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Modal -->
<div class="modal fade" id="tyreModal" tabindex="-1" role="dialog" aria-labelledby="tyreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tyreModalLabel">Tyre Numbers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="tyreModalBody">
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#date1,#date2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    
    $('#parts_id').select2({
        placeholder: 'Select Parts'
    });

    // Set 'All' as default selection
    $('#parts_id').val(['all']).trigger('change');

    // Setup - add a text input to each footer cell
    $('#myTable tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });

    // DataTable
    var table = $('#myTable').DataTable({
        "language": {
            "url": '{{ __("fleet.datatable_lang") }}',
        },
        initComplete: function () {
            // Apply the search
            this.api().columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        }
    });
	$(document).on('click', '.show-tyres', function() {
        var partId = $(this).data('part-id');
        var partName = $(this).data('part-name');

        // AJAX call to get tyre numbers
        $.ajax({
            url: '{{ route("get.tyre.numbers") }}',
            method: 'GET',
            data: { part_id: partId },
            success: function(response) {
                var modalContent = '<h6>' + partName + '</h6><hr>';
                if (response && response.length > 0) {
                    modalContent += '<p>' + response.join(', ') + '</p>';
                } else {
                    modalContent += '<p>No tyre numbers available</p>';
                }
                $('#tyreModalBody').html(modalContent);
                $('#tyreModal').modal('show');
            },
            error: function() {
                alert('Error fetching tyre numbers');
            }
        });
    });
});
  
</script>
@endsection
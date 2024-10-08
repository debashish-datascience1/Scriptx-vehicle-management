@extends('layouts.app')
@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
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
<li class="breadcrumb-item active">Vehicle Overview Report</li>
@endsection
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="card card-info">
			<div class="card-header">
				<h3 class="card-title">Vehicle Overview Report
				</h3>
			</div>

			<div class="card-body">
				{!! Form::open(['route' => 'reports.vehicles-overview','method'=>'post','class'=>'form-block']) !!}
				<div class="row newrow">
					<div class="col-md-4">
                        {{-- {{dd($vehicles)}} --}}
						<div class="form-group">
							{!! Form::label('vehicle_id', __('fleet.vehicle'), ['class' => 'form-label']) !!}
							{!! Form::select('vehicle_id',$vehicles,$request['vehicle_id'] ?? null,['class'=>'form-control','id'=>'vehicle_id','placeholder'=>'Select Vehicle','required']) !!}
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							{!! Form::label('date1','From',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								{!! Form::text('date1', $request['date1'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group" style="margin-right: 5px">
							{!! Form::label('date2','To',['class' => 'form-label dateShow']) !!}
							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  {!! Form::text('date2', $request['date2'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
					
				<div class="row newrow">
					<div class="col-md-12">
					<button type="button" id="generateReport" class="btn btn-info" style="margin-right: 10px">@lang('fleet.generate_report')</button>
					<button type="submit" formaction="{{url('admin/print-vehicle-overview-report')}}" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> @lang('fleet.print')</button>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</div>
<div id="reportContent">
	@if(isset($result))
	<div class="row">
		<div class="col-md-12">
			<div class="card card-info">
				<div class="card-header">
					<h3 class="card-title">
						@if(isset($all_vehicles))
							Fleet Overview Report
						@else
							Vehicle Overview Report
						@endif
					</h3>
				</div>

				<div class="card-body table-responsive">
					@if(isset($all_vehicles))
						<table class="table table-bordered table-striped table-hover" id="fleetOverviewTable">
							<thead>
								<tr>
									<th>Vehicle</th>
									<th>Bookings</th>
									<th>Total KM</th>
									<th>Revenue</th>
									<th>Fuel Usage</th>
									<th>Fuel Cost</th>
									<th>Maintenance</th>
									<th>Tyre Cost</th>
									<th>Net Profit</th>
								</tr>
							</thead>
							<tbody>
								@foreach($summary as $vehicle_data)
								<tr>
									<td>
										{{$vehicle_data['vehicle']->make}}-{{$vehicle_data['vehicle']->model}}
										<br>
										<small class="text-muted">{{$vehicle_data['vehicle']->license_plate}}</small>
									</td>
									<td>{{$vehicle_data['bookings_count']}}</td>
									<td>{{number_format($vehicle_data['total_kms'], 2)}} {{Hyvikk::get('dis_format')}}</td>
									<td>{{Hyvikk::get('currency')}} {{number_format($vehicle_data['total_revenue'], 2)}}</td>
									<td>{{number_format($vehicle_data['fuel_qty'], 2)}} {{Hyvikk::get('fuel_unit')}}</td>
									<td>{{Hyvikk::get('currency')}} {{number_format($vehicle_data['fuel_cost'], 2)}}</td>
									<td>
										{{$vehicle_data['work_orders']}} orders
										<br>
										<small class="text-muted">{{Hyvikk::get('currency')}} {{number_format($vehicle_data['maintenance_cost'], 2)}}</small>
									</td>
									<td>{{Hyvikk::get('currency')}} {{number_format($vehicle_data['tyre_cost'], 2)}}</td>
									<td>{{Hyvikk::get('currency')}} {{number_format($vehicle_data['net_profit'], 2)}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr class="table-info">
									<th>Total</th>
									<th>{{collect($summary)->sum('bookings_count')}}</th>
									<th>{{number_format(collect($summary)->sum('total_kms'), 2)}} {{Hyvikk::get('dis_format')}}</th>
									<th>{{Hyvikk::get('currency')}} {{number_format(collect($summary)->sum('total_revenue'), 2)}}</th>
									<th>{{number_format(collect($summary)->sum('fuel_qty'), 2)}} {{Hyvikk::get('fuel_unit')}}</th>
									<th>{{Hyvikk::get('currency')}} {{number_format(collect($summary)->sum('fuel_cost'), 2)}}</th>
									<th>{{collect($summary)->sum('work_orders')}} orders</th>
									<th>{{Hyvikk::get('currency')}} {{number_format(collect($summary)->sum('tyre_cost'), 2)}}</th>
									<th>{{Hyvikk::get('currency')}} {{number_format(collect($summary)->sum('net_profit'), 2)}}</th>
								</tr>
							</tfoot>
						</table>
						<div class="mt-4">
						@if(!isset($request['export']))
							@if(isset($pagination) && method_exists($pagination, 'links'))
								{{ $pagination->links() }}
							@endif
						@endif
						</div>
					@else
					<div class="card-body table-responsive">
						<table class="table table-bordered table-striped table-hover"  id="myTable1">
							{{-- Vehicle Overview Report --}}
							<tr>
								<td align="center" style="font-size:23px;">
									<strong>{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->license_plate}}</strong>
									@if(!empty($vehicle->driver))
										<br><span>{{ucwords(strtolower($vehicle->driver->assigned_driver->name))}}</span>
									@endif
									@if(!empty($vehicle->driver))
										<h6>{{Helper::getCanonicalDate($from_date)}} - {{Helper::getCanonicalDate($to_date)}}</h6>
									@endif
								</td>
							</tr>
							{{-- Fuel,Booking,Driver Advance,Expenes, Income --}}
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Bookings</td>
										</tr>
										<tr>
											<th>No. of Booking(s)</th>
											<th>Total KM</th>
											<th>Total Fuel</th>
											<th>Total Amount</th>
										</tr>
									</thead>
									<tbody>
										@if($book->totalbooking!=0 && !empty($book->totalbooking))
										<tr>
											<td>{{$book->totalbooking}} bookings</td>
											<td>{{$book->totalkms}} {{Hyvikk::get('dis_format')}}</td>
											<td>{{$book->totalfuel}} {{Hyvikk::get('fuel_unit')}}</td>
											<td>{{Hyvikk::get('currency')}} {{$book->totalprice}}</td>
										</tr>
										@else
										<tr>
											<td colspan="4" align='center' style="color: red">No Records Found...</td>
										</tr>
										@endif
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="4" align="center" style="font-size:18px;font-weight: 600;">Fuel</td>
										</tr>
										<tr>
											<th>Fuel Type</th>
											<th>No. of Refuel(s)</th>
											<th>Quantity</th>
											<th>Amount</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($fuels))
										@foreach($fuels as $k=>$fs)
										<tr>
											<td>{{$k}}</td>
											<td>{{count($fs->id)}} time(s)</td>
											<td>{{array_sum($fs->ltr)}} {{ $k!='Lubricant' ? Hyvikk::get('fuel_unit') : 'pc'}}</td>
											<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals(array_sum($fs->total))}}</td>
										</tr>
										@endforeach
										@else
										<tr>
											<td colspan="4" align='center' style="color: red">No Records Found...</td>
										</tr>
										@endif
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="3" align="center" style="font-size:18px;font-weight: 600;">Driver Advance</td>
										</tr>
									</thead>
									<tbody>
										@if(!empty($advances->details))
										{{-- @foreach($advances as $k=>$ad) --}}
										<tr>
											{{-- <td rowspan="{{array_sum($advances->details)}}">{{$advances->times}} times</td>
											<td>{{array_sum($advances->amount)}}</td> --}}
											<td>
												<table class="table tabl-bordered table-striped">
													<thead>
														<th>#</th>
														<th>Head</th>
														<th>No. of Time(s)</th>
														<th>Amount</th>
													</thead>
													<tbody>
														@foreach($advances->details as $k=>$det)
														<tr>
															<td>{{$k+1}}</td>
															<td>{{$det->label}}</td>
															<td>{{$det->times}}</td>
															<td>{{Hyvikk::get('currency')}} {{!empty($det->amount) ? Helper::properDecimals($det->amount) : Helper::properDecimals(0)}}</td>
														</tr>
														@endforeach
														<tr>
															<th colspan="3" style="text-align:right;">Total</th>
															<th>{{Hyvikk::get('currency')}} {{!empty($advances->amount) ? Helper::properDecimals(array_sum($advances->amount)) : Helper::properDecimals(0)}}</th>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										{{-- @endforeach --}}
										@else
										<tr>
											<td colspan="4" align='center' style="color: red">No Records Found...</td>
										</tr>
										@endif
									</tbody>
								</table>
							</tr>
							<tr>
								<table class="table table-bordered table-striped">
									
									<thead>
										<tr>
											<td colspan="6" align="center" style="font-size:18px;font-weight: 600;">Work Order</td>
										</tr>
										<tr>
											<th>No. of Work Order(s)</th>
											<th>GST</th>
											<th>Total</th>
											<th>No. of Vendors</th>
											<th>Status</th>
											<th>Parts Used</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($wo->count) && $wo->count!=0)
										{{-- @foreach($wo as $k=>$w) --}}
										<tr>
											<td>{{$wo->count}}</td>
											<td>
												<table class="table table-striped">
													<tr>
														<th>CGST</th>
														<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($wo->cgst)}}</td>
													</tr>
													<tr>
														<th>SGST</th>
														<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($wo->sgst)}}</td>
													</tr>
												</table>
											</td>
											<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($wo->grand_total)}}</td>
											<td>{{$wo->vendors}}</td>
											<td>
												<table class="table table-striped">
													@foreach($wo->status as $k=>$s)
													<tr>
														<th>{{$k}}</th>
														<td>{{count($s)}}</td>
													</tr>
													@endforeach
												</table>
											</td>
											<td>
												<table class="table table-striped table-bordered">
													<thead>
														<tr>
															<th>Part</th>
															<th>Quantity</th>
															<th>Amount</th>
														</tr>
													</thead>
													<tbody>
														@if(empty($partsUsed))
														@foreach($partsUsed as $pu)
														<tr>
															<td>{{$pu->part->title}}</td>
															<td>{{$pu->qty}}</td>
															<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($pu->total)}}</td>
														</tr>
														@endforeach
														@else
														<tr>
															<td colspan="3" align='center' style="color: red">No Parts Used...</td>
														</tr>
														@endif
													</tbody>
												</table>
											</td>
										</tr>
										{{-- @endforeach --}}
										@else
										<tr>
											<td colspan="6" align='center' style="color: red">No Records Found...</td>
										</tr>
										@endif
								</tbody>
							</table>
						</tr>
					</table>
					@endif
				</div>
			</div>
		</div>
	</div>
	@endif
</div>

<div class="modal fade" id="wheelPriceModal" tabindex="-1" role="dialog" aria-labelledby="wheelPriceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="wheelPriceModalLabel">Wheel Prices Review</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Wheel Name</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody id="wheelPriceTableBody">
              <!-- Wheel data will be inserted here -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-info" id="continueReport">Continue with Report</button>
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
  $(document).ready(function() {
    $('#date1').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
  });
</script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cdn/buttons.html5.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#vehicle_id").select2();
		$('#myTable tfoot th').each( function () {
	      var title = $(this).text();
	      $(this).html( '<input type="text" placeholder="'+title+'" />' );
	    });
	    var myTable = $('#myTable').DataTable( {
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
	});


	$(document).ready(function() {
    // Initialize datepickers
    $('#date1').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });

    // Initialize select2
    $("#vehicle_id").select2();

    // Initialize DataTables with server-side pagination configuration
    if ($('#fleetOverviewTable').length) {
        var table = $('#fleetOverviewTable').DataTable({
            dom: 'Bfrtip',
            paging: false, // Disable DataTables pagination
            buttons: [
                {
                    extend: 'copy',
                    action: function(e, dt, button, config) {
                        exportFullData('copy');
                    }
                },
                {
                    extend: 'csv',
                    action: function(e, dt, button, config) {
                        exportFullData('csv');
                    }
                },
                {
                    extend: 'excel',
                    action: function(e, dt, button, config) {
                        exportFullData('excel');
                    }
                },
                {
                    extend: 'pdf',
                    action: function(e, dt, button, config) {
                        exportFullData('pdf');
                    }
                }
            ],
            "language": {
                "url": '{{ __("fleet.datatable_lang") }}',
            }
        });
    }

    // Handle server-side pagination clicks
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        // Get current form data
        var formData = new FormData($('form.form-block')[0]);
        
        // Make AJAX request instead of form submission
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Update only the table content
                $('#reportContent').html($(response).find('#reportContent').html());
                
                // Update the URL without page refresh
                window.history.pushState({}, '', url);
            },
            error: function(xhr) {
                console.error('Error loading page:', xhr);
            }
        });
    });
});

function exportFullData(type) {
    var formData = $('form.form-block').serialize();
    formData += '&export=' + type;

    var form = $('<form>', {
        'method': 'POST',
        'action': $('form.form-block').attr('action')
    });

    $.each($('form.form-block').serializeArray(), function(i, field) {
        form.append($('<input>', {
            'type': 'hidden',
            'name': field.name,
            'value': field.value
        }));
    });

    form.append($('<input>', {
        'type': 'hidden',
        'name': 'export',
        'value': type
    }));

    form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
    }));

    $('body').append(form);
    form.submit();
    form.remove();
}

	// Preserve form data in pagination links
	$(document).on('click', '.pagination a', function(e) {
		e.preventDefault();
		var url = $(this).attr('href');
		
		// Get current form data
		var formData = $('form.form-block').serialize();
		
		// Append form data to pagination URL
		url += (url.indexOf('?') === -1 ? '?' : '&') + formData;
		
		// Submit form to new URL
		$('form.form-block').attr('action', url).submit();
	});
	$(document).ready(function() {
    // Store the original form
    var originalForm = $('form.form-block').get(0);
    
    // Function to handle report generation
    function handleReportGeneration(e, isPrint) {
        e.preventDefault();
        loadWheelPrices(isPrint);
        return false;
    }

    // Attach event handlers to both buttons
    $('#generateReport').on('click', function(e) {
        handleReportGeneration(e, false);
    });

    $('button[formaction][formtarget="_blank"]').on('click', function(e) {
        handleReportGeneration(e, true);
    });

    function loadWheelPrices(isPrint) {
        $.ajax({
            url: '/VehicleMgmt/admin/reports/get-wheels',
            method: 'GET',
            success: function(response) {
                populateWheelModal(response.wheels);
                $('#wheelPriceModal').modal('show');
                // Store whether it's a print action
                $('#wheelPriceModal').data('isPrint', isPrint);
            },
            error: function(xhr) {
                console.error('Error loading wheel data:', xhr);
                alert('Error loading wheel data. Please try again.');
            }
        });
    }

    function populateWheelModal(wheels) {
        var tbody = $('#wheelPriceTableBody');
        tbody.empty();
        
        wheels.forEach(function(wheel) {
            var row = `
                <tr>
                    <td>${wheel.name}</td>
                    <td>
                        <input type="number" class="form-control wheel-price" 
                               data-wheel-id="${wheel.id}" 
                               value="${wheel.price}" 
                               step="0.01" min="0">
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    $('#continueReport').on('click', function() {
        var updatedWheels = {};
        $('.wheel-price').each(function() {
            updatedWheels[$(this).data('wheel-id')] = parseFloat($(this).val());
        });

        // Add the updated wheel prices to the form
        $('<input>').attr({
            type: 'hidden',
            name: 'wheel_prices',
            value: JSON.stringify(updatedWheels)
        }).appendTo('form.form-block');

        $('#wheelPriceModal').modal('hide');

        var isPrint = $('#wheelPriceModal').data('isPrint');
        if (isPrint) {
            // For print, submit the form to the print URL
            $('form.form-block').attr('action', $('button[formaction][formtarget="_blank"]').attr('formaction'));
            $('form.form-block').attr('target', '_blank');
        } else {
            // For generate report, use the default form action
            $('form.form-block').attr('action', originalForm.action);
            $('form.form-block').removeAttr('target');
        }

        $('form.form-block').off('submit').submit();
    });
});
</script>
@endsection


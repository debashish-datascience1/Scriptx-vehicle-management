
<div role="tabpanel">
    <ul class="nav nav-pills" style="margin-bottom: 10px;">
        <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#address-tab" data-toggle="tab" class="nav-link custom_padding"> @lang('fleet.vehicleDocs') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#acq-tab" data-toggle="tab" class="nav-link custom_padding"> @lang('fleet.purchase_info') <i class="fa"></i></a>
        </li>

        {{-- <li class="nav-item"><a href="#reviews" data-toggle="tab" class="nav-link custom_padding"> @lang('fleet.vehicle_inspection') <i class="fa"></i></a>
        </li> --}}
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>@lang('fleet.vehicle')</th>
					<td>{{$vehicle->make}}</td>
				</tr>

				<tr>
					<th>@lang('fleet.model')</th>
					<td>
						{{$vehicle->model}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.type')</th>
					<td>
						{{$vehicle->types['displayname']}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.year')</th>
					<td>
						{{$vehicle->year}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.average') (@lang('fleet.mpg'))</th>
					<td>
						{{$vehicle->average}} km/L
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.average') (@lang('fleet.tpl'))</th>
					<td>
						{{$vehicle->time_average}} hour(s)/L
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.intMileage') ({{Hyvikk::get('dis_format')}})</th>
					<td>
						{{$vehicle->int_mileage}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.vehicleImage')</th>
					<td>
						@if($vehicle->vehicle_image != null)
			            <a href="{{ asset('uploads/'.$vehicle->vehicle_image) }}" target="_blank" class="col-xs-3 control-label">View</a>
			            @endif
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.engine')</th>
					<td>
						{{$vehicle->engine_type}}
					</td>
				</tr>

				{{-- <tr>
					<th>@lang('fleet.horsePower')</th>
					<td>
						{{$vehicle->horse_power}}
					</td>
				</tr> --}}

				<tr>
					<th>@lang('fleet.color')</th>
					<td>
						{{$vehicle->color}}
					</td>
				</tr>

				{{-- <tr>
					<th>@lang('fleet.vin')</th>
					<td>
						{{$vehicle->vin}}
					</td>
				</tr> --}}

				<tr>
					<th>@lang('fleet.engine_no')</th>
					<td>
						{{$vehicle->engine_no}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.chassis_no')</th>
					<td>
						{{$vehicle->chassis_no}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.licenseNo')</th>
					<td>
						{{$vehicle->license_plate}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.lic_exp_date')</th>
					<td>
						@if($vehicle->lic_exp_date)
						{{date(Hyvikk::get('date_format'),strtotime($vehicle->lic_exp_date))}}
						@endif
					</td>
				</tr>

				{{-- <tr>
					<th>@lang('fleet.reg_exp_date')</th>
					<td>
						@if($vehicle->reg_exp_date)
						{{date(Hyvikk::get('date_format'),strtotime($vehicle->reg_exp_date))}}
						@endif
					</td>
				</tr> --}}

				<tr>
					<th>@lang('fleet.assigned_driver')</th>
					<td>
						@if($vehicle->getMeta('driver_id'))
							{{ucwords($vehicle->driver->assigned_driver->name)}}
						@else
							<span class="badge badge-warning">@lang('fleet.notAssigned')</span>
						@endif
					</td>
				</tr>
				<tr>
					<th>Vehicle Owner</th>
					<td>
						@if($vehicle->getMeta('owner_name'))
							{{ucwords($vehicle->owner_name)}}
						@endif
					</td>
				</tr>
				<tr>
					<th>Owner Phone Number</th>
					<td>
						@if($vehicle->getMeta('owner_name'))
							{{ucwords($vehicle->owner_number)}}
						@endif
					</td>
				</tr>
				<tr>
					<th>RC Number</th>
					<td>
						@if($vehicle->getMeta('owner_name'))
							{{ucwords($vehicle->rc_number)}}
						@endif
					</td>
				</tr>
				<tr>
					<th>RC Image</th>
					<td>
						@if($vehicle->rc_image != null)
			            <a href="{{ asset('uploads/'.$vehicle->rc_image) }}" target="_blank" class="col-xs-3 control-label">View</a>
			            @endif
					</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		<div class="tab-pane" id="address-tab" >
			<table class="table table-striped">
				<tr>
					<th>@lang('fleet.vehicle')</th>
					<td>
					{{$vehicle->make}}-{{$vehicle->model}}-{{$vehicle->types['displayname']}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.insuranceNumber')</th>
					<td>
					{{$vehicle->getMeta('ins_number')}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.inc_doc')</th>
					<td>
					@if($vehicle->getMeta('documents') != null)
					<a href="{{ asset('uploads/'.$vehicle->getMeta('documents')) }}" target="_blank">
					View
					</a>
					@endif
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.inc_expirationDate')</th>
					<td>
						@if($vehicle->getMeta('ins_exp_date'))
							{{date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('ins_exp_date')))}}
						@endif
					</td>
				</tr>
				{{-- Fitness Tax --}}
				<tr>
					<th>@lang('fleet.fitnessTax')</th>
					<td>
					{{$vehicle->getMeta('fitness_tax')}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.fitnessDocuments')</th>
					<td>
					@if($vehicle->getMeta('fitness_taxdocs') != null)
					<a href="{{ asset('uploads/'.$vehicle->getMeta('fitness_taxdocs')) }}" target="_blank">
					View
					</a>
					@endif
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.fitnessExpirationDate')</th>
					<td>
						@if($vehicle->getMeta('fitness_expdate'))
							{{date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('fitness_expdate')))}}
						@endif
					</td>
				</tr>
				{{-- Road Tax --}}
				<tr>
					<th>@lang('fleet.roadTax')</th>
					<td>
					{{$vehicle->getMeta('road_tax')}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.roadTaxDocuments')</th>
					<td>
					@if($vehicle->getMeta('road_docs') != null)
					<a href="{{ asset('uploads/'.$vehicle->getMeta('road_docs')) }}" target="_blank">
					View
					</a>
					@endif
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.roadTaxExpDate')</th>
					<td>
						@if($vehicle->getMeta('road_expdate'))
							{{date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('road_expdate')))}}
						@endif
					</td>
				</tr>

				{{-- Permit --}}
				<tr>
					<th>@lang('fleet.permitNumber')</th>
					<td>
					{{$vehicle->getMeta('permit_number')}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.permitDocuments')</th>
					<td>
					@if($vehicle->getMeta('permit_docs') != null)
					<a href="{{ asset('uploads/'.$vehicle->getMeta('permit_docs')) }}" target="_blank">
					View
					</a>
					@endif
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.permitExpDate')</th>
					<td>
						@if($vehicle->getMeta('permit_expdate'))
							{{date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('permit_expdate')))}}
						@endif
					</td>
				</tr>
				{{-- Pollution --}}
				<tr>
					<th>@lang('fleet.pollutionNumber')</th>
					<td>
					{{$vehicle->getMeta('pollution_tax')}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.pollutionDocuments')</th>
					<td>
					@if($vehicle->getMeta('pollution_docs') != null)
					<a href="{{ asset('uploads/'.$vehicle->getMeta('pollution_docs')) }}" target="_blank">
					View
					</a>
					@endif
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.pollutionExpDate')</th>
					<td>
						@if($vehicle->getMeta('pollution_expdate'))
							{{date(Hyvikk::get('date_format'),strtotime($vehicle->getMeta('pollution_expdate')))}}
						@endif
					</td>
				</tr>
				{{-- Fast Tag --}}
				<tr>
					<th>@lang('fleet.fastTagNumber')</th>
					<td>
					{{$vehicle->getMeta('fast_tag')}}
					</td>
				</tr>

				<tr>
					<th>@lang('fleet.fastTagDoc')</th>
					<td>
					@if($vehicle->getMeta('fasttag_docs') != null)
					<a href="{{ asset('uploads/'.$vehicle->getMeta('fasttag_docs')) }}" target="_blank">
					View
					</a>
					@endif
					</td>
				</tr>

				{{-- GPS --}}
				<tr>
					<th>@lang('fleet.gpsNumber')</th>
					<td>
					{{$vehicle->getMeta('gps_number')}}
					</td>
				</tr>
			</table>
			
		</div>
		<!--tab2-->

		{{-- tab3 --}}
		<div class="tab-pane " id="acq-tab" >
			<table class="table table-striped">
				@if(!empty($purch_info))
				<tr>
					<th>Vehicle</th>
					<td>{{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}</td>
				</tr>
				<tr>
					<th>Purchase Date</th>
					<td>{{Helper::getCanonicalDate($purch_info->purchase_date)}}</td>
				</tr>
				<tr>
					<th>Loan Date</th>
					<td>{{Helper::getCanonicalDate($purch_info->loan_date)}}</td>
				</tr>
				<tr>
					<th>Loan Account</th>
					<td>{{$purch_info->loan_account}}</td>
				</tr>
				<tr>
					<th>Vehicle Cost</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($purch_info->vehicle_cost)}}</td>
				</tr>
				<tr>
					<th>Loan Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($purch_info->loan_amount)}}</td>
				</tr>
				<tr>
					<th>Down Payment</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($purch_info->amount_paid)}}</td>
				</tr>
				<tr>
					<th>Bank</th>
					<td><strong>{{$purch_info->bank_name}}</strong></td>
				</tr>
				<tr>
					<th>EMI Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($purch_info->emi_amount)}}</td>
				</tr>
				<tr>
					<th>EMI Date</th>
					<td>{{Helper::getCanonicalDate($purch_info->emi_date)}}</td>
				</tr>
				<tr>
					<th>Loan Duration</th>
					<td>{{$purch_info->loan_duration}}</td>
				</tr>
				<tr>
					<th>GST Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($purch_info->gst_amount)}}</td>
				</tr>
				@else
				<tr>
					<th class="text-center" style="text-align:left">No Purchase details found...</th>
				</tr>
				@endif
			</table>
		</div>

		<!--tab3-->

		<!-- tab4 -->
		<div class="tab-pane " id="reviews" >
			<div class="card card-default">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12">
						@foreach($vehicle->reviews as $r)
							<a href="{{url('admin/view-vehicle-review/'.$r->id)}}" class="btn btn-success" style="margin-bottom: 5px;" title="View Review">@lang('fleet.reg_no'): {{$r->reg_no}}</a>
							&nbsp; <a href="{{url('admin/print-vehicle-review/'.$r->id)}}" class="btn btn-danger" target="_blank" title="@lang('fleet.print')" style="margin-bottom: 5px;"><i class="fa fa-print"></i> @lang('fleet.print')</a>
							<br>
						@endforeach
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- tab4 -->
	</div>
</div>

<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active advgeneral"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>
     @if(count($advanced)>0)
        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding advbook">Booking Advances <i class="fa"></i></a>
        </li>
     @endif
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver</th>
					<td>
						{{-- {{dd($payroll->user_id)}} --}}
						@if(!empty($payroll->user_id))
							{{$payroll->driver->name}}
						@else
							<span class="badge badge-danger">No Driver Found</span>
						@endif
					</td>
					<th>Vehicle</th>
					<td>
						@if(!empty($payroll->driver_vehicle))
							{{$payroll->driver_vehicle->vehicle->make}}-{{$payroll->driver_vehicle->vehicle->model}}-{{$payroll->driver_vehicle->vehicle->license_plate}}
						@else
							<span class="badge badge-danger">No Vehicle Assigned</span>
						@endif
						{{-- {{dd($row->driver_vehicle->vehicle->make)}} --}}
					</td>
				</tr>
                <tr>
					<th>For Month</th>
					<td>
						@php $month = $payroll->for_month<10 ? "0".$payroll->for_month:$payroll->for_month;  @endphp
                        {{date("F-Y",strtotime($payroll->for_year."-".$month."-01"))}}
					</td>
					<th>Working Days</th>
					<td>
						{{$payroll->working_days!='' ? $payroll->working_days :'0'}} days
					</td>
				</tr>
                <tr>
					<th>Monthly Salary</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->salary,1,2)}}</td>
					<th>Absent Days</th>
					<td>
						{{$payroll->absent_days!='' ? $payroll->absent_days :'0'}} days
					</td>
				</tr>
				<tr>
					<th>Total Payable Salary</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->total_payable_salary,1,2)}}</td>
					<th>Carried Salary</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->carried_salary,1,2)}}</td>
				</tr>
				<tr>
					<th>Paid Salary</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->payable_salary,1,2)}}</td>
					<th>Remaining Salary</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->remaining_salary,1,2)}}</td>
				</tr>
				<tr>
					<th>Salary Advance</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->advance_salary,1,2)}}</td>
					<th>Booking Advance</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->advance_driver,1,2)}}</td>
				</tr>
				<tr>
					<th>Deducted Salary</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->deduct_salary,1,2)}}</td>
					<th>Remarks</th>
					<td>
						{{$payroll->payroll_remarks}}
					</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

        @if(!empty($advanced[0]))
		<!--tab2-->
		<div class="tab-pane" id="prev-tab" >
            {{-- @foreach($advanced as $advance)
                <table class="table table-striped">
                    @foreach($advance as $adv)
                    <tr>
                        <th>{{$adv->param_name->label}}</th>
                        <td>{{$adv->value}}</td>
                    </tr>
                    @endforeach 
                </table>
            @endforeach  --}}

            <table class="table table-striped">
			@if(isset($advanced) || !empty($advanced))
				@foreach($advheads as $key=>$val)
					<tr>
						<th>{{$val->label}}</th>
						@foreach($advanced as $advance)
							@foreach($advance as $adv)
								@if($adv->param_id==$val->id)
									<td>{{$adv->value}}</td>
								@endif
							@endforeach
						@endforeach 
					</tr>
				@endforeach
			@else
				<tr>
					<th colspan="2" style="color: red;">No advances were given..</th>
				</tr>
			@endif
            </table>
		</div>
		@else
		<div class="tab-pane" id="prev-tab" >
			<table>
				<tr>
					<td colspan="2" style="color: red;"><i>No <strong>Advances</strong> were given to driver during bookings....</i></td>
				</tr>
			</table>
		</div>
        @endif
</div>
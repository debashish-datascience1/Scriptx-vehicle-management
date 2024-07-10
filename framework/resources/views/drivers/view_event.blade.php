
{{-- <div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active advgeneral"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>
        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding advbook">Attendance <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab"> --}}
		{{-- {{dd($meta['emp_id'])}} --}}
			<table class="table table-striped">
				<tr>
					<th>Driver ID</th>
					<td><strong>{{strtoupper($driver->getMeta('emp_id'))}}</strong></td>
				</tr>
				<tr>
					<th>Driver</th>
					<td><strong>{{ucwords($driver->name)}}</strong></td>
				</tr>
				<tr>
					<th>License</th>
					<td>{{ucwords($driver->getMeta('license_number'))}}</td>
				</tr>
				<tr>
					<th>Vehicle</th>
					<td>
						@if(!empty($vehicle))
							{{$vehicle->make}} - {{$vehicle->model}} - {{$vehicle->license_plate}}
						@else
							<span class="badge badge-danger">N/A</span>
						@endif
					</td>
				</tr>
				<tr>
					<th>Gender</th>
					<td>
						@if($driver->getMeta('gender')==1)
							Male
						@elseif($driver->getMeta('gender')==0)
							Female
						@endif
					</td>
				</tr>
				<tr>
					<th>Salary</th>
					<td>{{($driver->getMeta('salary'))}}</td>
				</tr>
				<tr>
					<th>Phone</th>
					<td>{{$driver->getMeta('phone_code')}} {{$driver->getMeta('phone')}}</td>
				</tr>
				<tr>
					<th>Email</th>
					<td>{{$driver->getMeta('email')}}</td>
				</tr>
				<tr>
					<th>Address</th>
					<td>{{$driver->getMeta('address')}}</td>
				</tr>
			</table>
		{{-- </div>
		<!--tab1-->

        
		<div class="tab-pane" id="prev-tab" >

            
		</div>
        
</div> --}}
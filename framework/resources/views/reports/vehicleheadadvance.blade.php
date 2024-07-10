<table class="table table-striped" >
    
	<thead class="thead-inverse">
		<tr>
		  <td colspan="6">
			{!! Form::open(['method'=>'post','class'=>'form-inline']) !!}
			<input type="hidden" name="param_id" value="{{$param->id}}">
			<input type="hidden" name="vehicle_id" value="{{$vehicle->id}}">
			<input type="hidden" name="from_date" value="{{$from_date}}">
			<input type="hidden" name="to_date" value="{{$to_date}}">  
		  
			<button  type="submit" formaction="{{url('admin/print-vehicle-head-advance-report')}}" formtarget="_blank" class="btn btn-danger" style="margin-left:665px;"><i class="fa fa-print"></i> @lang('fleet.print')</button>
			{!! Form::close() !!} 
		  </td>
		</tr>
		<tr>
		  <th>SL#</th>
		  <th>Date</th>
		  <th>Vehicle</th>
		  <th>Head</th>
		  <th>Amount</th>
		</tr>
	  </thead>
	  <tbody>
		@foreach($advanceDriver as $k=>$row)
		<tr>
		  <td>{{$k+1}}</td>
		  <td>{{Helper::getCanonicalDateTime($row->booking->pickup,'default')}}</td>
		  <td>{{$vehicle->make}}-{{$vehicle->model}}-<strong>{{$vehicle->license_plate}}</strong></td>
		  <td>{{$row->param_name->label}}</td>
		  <td>{{Hyvikk::get('currency')}} {{bcdiv($row->value,1,2)}}</td>
		</tr>
		@endforeach
		<tr>
			<th colspan="3"></th>
			<th>Grand Total</th>
			<th>{{Hyvikk::get('currency')}} {{bcdiv($advanceDriver->sum('value'),1,2)}}</th>
		</tr>
	  </tbody>
	</table>
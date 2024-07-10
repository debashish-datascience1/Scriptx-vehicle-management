{{-- 
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding">Calculations<i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab"> --}}
			<table class="table table-striped">
				<tr>
					<th>Created On </th>
					<td>{{Helper::getCanonicalDate($row->created_at)}}</td>
				</tr>
                <tr>
					<th>Required By </th>
					<td>{{Helper::getCanonicalDate($row->required_by)}}</td>
				</tr>
				<tr>
					<th>Vehicle</th>
					<td>{{$row->vehicle->make}}-{{$row->vehicle->model}}-{{$row->vehicle->license_plate}}</td>
				</tr>
                <tr>
					<th>Vendor</th>
					<td>{{$row->vendor->name}}</td>
				</tr>
                <tr>
					<th>Price</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->price)}}</td>
				</tr>
                @if(!empty($row->cgst))
                <tr>
                    <th>Is GST ?</th>
                    <td>
                        @if($row->is_gst==1)
                        <span class="badge badge-success">Yes</span>
                        @else
                        <span class="badge badge-danger">No</span>
                        @endif
                    </td>
                </tr>
                @endif
                @if(!empty($row->cgst))
                <tr>
					<th>CGST Rate</th>
					<td>{{$row->cgst}} %</td>
				</tr>
                <tr>
					<th>CGST Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->cgst_amt)}}</td>
				</tr>
                @endif
                @if(!empty($row->sgst))
                <tr>
					<th>SGST Rate</th>
					<td>{{$row->sgst}} %</td>
				</tr>
                <tr>
					<th>SGST Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->sgst_amt)}}</td>
				</tr>
                @endif
                @if(!empty($row->cgst) || !empty($row->sgst))
                <tr>
					<th>GST Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->cgst_amt+$row->sgst_amt)}}</td>
				</tr>
                <tr>
					<th>Total Amount</th>
					<td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->price+$row->cgst_amt+$row->sgst_amt)}}</td>
				</tr>
                @endif
				<tr>
					<th>Description</th>
					<td>{{$row->description}}</td>
				</tr>
                <tr>
					<th>Meter</th>
					<td>{{$row->meter}}</td>
				</tr>
                <tr>
					<th>Note</th>
					<td>{{$row->note}}</td>
				</tr>
                <tr>
					<th>Status</th>
					<td>{{$row->status}}</td>
				</tr>
				{{-- <tr>
					<th>Remarks</th>
					<td>
						{{$leave->remarks!="" ? $leave->remarks : "N/A"}}
					</td>
				</tr> --}}
			</table>
		{{-- </div>
</div> --}}
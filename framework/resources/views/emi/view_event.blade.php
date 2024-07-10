
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#emis-tab" data-toggle="tab" class="nav-link custom_padding"> EMI History <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Vehicle</th>
					<td>{{$emi->vehicle->license_plate}}</td>
				</tr>
				<tr>
					<th>Driver</th>
					<td>{{!empty($emi->driver) ? $emi->driver->name : ''}}</td>
				</tr>
				<tr>
					<th>Amount</th>
					<td>{{Hyvikk::get('currency')}} {{bcdiv($emi->amount,1,2)}}</td>
				</tr>

				<tr>
					<th>Due Date</th>
					<td>{{Helper::getCanonicalDate($emi->date,'default')}}</td>
				</tr>
				<tr>
					<th>Paid Date</th>
					<td>{{Helper::getCanonicalDate($emi->pay_date,'default')}}</td>
				</tr>

				<tr>
					<th>Bank</th>
					<td>{{!empty($emi->bank) ? $emi->bank->bank : ''}}</td>
				</tr>

				<tr>
					<th>Reference No</th>
					<td>{{$emi->reference_no}}</td>
				</tr>

				<tr>
					<th>Remarks</th>
					<td>{{$emi->remarks!="" ? $emi->remarks : ''}}</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		<div class="tab-pane" id="emis-tab" >
			<table class="table table-striped">
				<tr>
					<th>Due Date</th>
					<th>Paid Date</th>
					<th>Amount</th>
					<th>Bank</th>
                    <th>Reference No</th>
				</tr>
                @foreach($emis as $emi)
                    <tr>
                        <th>{{Helper::getCanonicalDate($emi->date,'default')}}</th>
                        <th>{{Helper::getCanonicalDate($emi->pay_date,'default')}}</th>
                        <td>{{Hyvikk::get('currency')}} {{bcdiv($emi->amount,1,2)}}</td>
                        <td>{{!empty($emi->bank) ? $emi->bank->bank : ''}}</td>
                        <td>{{$emi->remarks!="" ? $emi->remarks : ''}}</td>
                    </tr>
                @endforeach
			</table>
		</div>
</div>
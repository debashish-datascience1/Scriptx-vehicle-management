
<div role="tabpanel">
	{{-- {{dd($adv->adjust_advance)}} --}}
	@if($adv->adjust_advance)
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active gen"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>
        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding adjustments"> Adjustment <i class="fa"></i></a>
        </li>
    </ul>
	@endif
	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver</th>
					<td>{{$adv->driver->name}}</td>
				</tr>
				<tr>
					<th>Bank</th>
					<td>{{$adv->bank_details->bank}}({{$adv->bank_details->account_no}})</td>
				</tr>
				<tr>
					<th>Method</th>
					<td>{{$adv->method_param->label}}</td>
				</tr>
				<tr>
					<th>Amount</th>
					<td>{{Helper::properDecimals($adv->amount)}}</td>
				</tr>
				<tr>
					<th>Date</th>
					<td>{{Helper::getCanonicalDate($adv->date,'default')}}</td>
				</tr>
				<tr>
					<th>Status</th>
					<td>
						@if($adv->is_adjusted==1)
							<span class="badge badge-success">Completed</span>
						@elseif($adv->is_adjusted==2)
							<span class="badge badge-primary">In Progress</span>
						@elseif($adv->is_adjusted==null)
							<span class="badge badge-danger">Not Yet Done</span>
						@endif
					</td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td>{{$adv->remarks}}</td>
				</tr>
				<tr>
					<th>Created On</th>
					<td>{{Helper::getCanonicalDateTime($adv->created_at)}}</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		@if($adv->adjust_advance)
		<div class="tab-pane" id="prev-tab" >
			<table class="table table-striped">
				<tr>
					<th>Head</th>
					<th>Amount</th>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Bank</th>
                    <th>Ref. No</th>
                    <th>Date</th>
                    <th>Remarks</th>
				</tr>
                @foreach($adv->adjust_advance as $adj)
                    <tr>
                        <td>{{$adj->head}}</td>
                        <td>{{Helper::properdecimals($adj->amount)}}</td>
                        <td>{{$adj->method_param->label}}</td>
                        <td>{{$adj->payment_type->label}}</td>
                        <td>
							@if(!empty($adj->bank_details))
							{{$adj->bank_details->bank}}<br>
							{{$adj->bank_details->account_no}}
							@endif
						</td>
						<td>{{$adj->ref_no}}</td>
						<td>{{Helper::getCanonicalDate($adj->date,'default')}}</td>
						<td>{{$adj->remarks}}</td>
                    </tr>
                @endforeach
			</table>
			
		</div>
		@endif
</div>
{{-- <div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding"> Attendance History <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab"> --}}
		@foreach($getdet as $trans)
			<table class="table table-striped">
				<tr>
					<th>Transaction ID</th>
					<td>{{$trans->transaction_id}}</td>
				</tr>
                <tr>
					<th>Operation</th>
					<td>
						{{$trans->params->label}}
					</td>
				</tr>
                <tr>
                    {{-- <th colspan="{{count($incomes)}}">Method</th> --}}
                    <th>Method</th>
                    {{-- @foreach($incomes as $income) --}}
					<td>
						{{$income->method->label}}
					</td>
                    {{-- @endforeach --}}
				</tr>
				<tr>
					<th>Type</th>
					<td>
                        @if($trans->type==23)
                            <span class="badge badge-success">{{$trans->pay_type->label}}</span>
                        @elseif($trans->type==24)
                            <span class="badge badge-danger">{{$trans->pay_type->label}}</span>
                        @endif
					</td>
				</tr>
                <tr>
					<th>Previous</th>
					<td>
						{{Hyvikk::get('currency')}} {{number_format($trans->prev,2,'.','')}}
					</td>
				</tr>
                <tr>
					<th>Total</th>
					<td>
						{{Hyvikk::get('currency')}} {{number_format($trans->total,2,'.','')}}
					</td>
				</tr>
                <tr>
					<th>Grand Total</th>
					<td>
						{{Hyvikk::get('currency')}} {{number_format($trans->grandtotal,2,'.','')}}
					</td>
				</tr>
				@if($trans->advance_for==22)
				<tr>
					<th>Advance ? </th>
					<td>
						{{Hyvikk::get('currency')}} {{ empty($trans->income_expense) ? 0 : number_format($trans->income_expense->amount,2,'.','')}}
					</td>
				</tr>
				@endif
                <tr>
					<th>Date</th>
					<td>
						{{$income->date}}
						{{Helper::getCanonicalDate($income->date,'default')}}
					</td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td>
						{{$income->remarks}}
					</td>
				</tr>
			</table>
		@endforeach
		</div>
		<!--tab1-->
</div>
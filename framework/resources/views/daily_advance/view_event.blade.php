
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding advgeneral active"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link advsalary custom_padding"> Advance History <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver Name</th>
					<td>{{$advance->driver->name}}</td>
				</tr>

				<tr>
					<th>Date</th>
					<td>
						{{Helper::getCanonicalDate($advance->date,'default')}}
					</td>
				</tr>

                <tr>
					<th>Amount <span class="fa fa-inr"></span></th>
					<td>
						{{Hyvikk::get('currency')}} {{$advance->amount}}
					</td>
				</tr>

				<tr>
					<th>Payroll </th>
					<td>
						@if($advance->payroll_check==1)
                            <span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
                        @else
                            <span class="badge badge-danger"><i class="fa fa-times"></i> Not Adjusted</span>
                        @endif
					</td>
				</tr>

				<tr>
					<th>Remarks</th>
					<td>
						{{$advance->remarks!="" ? $advance->remarks : "N/A"}}
					</td>
				</tr>
			</table>
		</div>
		<!--tab1-->

		<!--tab2-->
		<div class="tab-pane" id="prev-tab" >
			<table class="table table-striped">
				<tr>
					<th>Date</th>
					<th>Amount</th>
					<th>Payroll</th>
                    <th>Remarks</th>
				</tr>
                @foreach($historys as $hist)
                    <tr>
                        <th>{{Helper::getCanonicalDate($hist->date,'default')}}</th>
                        <th>{{Hyvikk::get('currency')}} {{bcdiv($hist->amount,1,2)}}</th>
                        <td>
                            @if($hist->payroll_check==1)
                                <span class="badge badge-success"><i class="fa fa-check"></i> Adjusted</span>
                            @else
                                <span class="badge badge-danger"><i class="fa fa-times"></i> Not Adjusted</span>
                            @endif
                        </td>
                        <td>
                            {{$hist->remarks!="" ? $hist->remarks : "N/A"}}
                        </td>
                    </tr>
                @endforeach
			</table>
			
		</div>
</div>
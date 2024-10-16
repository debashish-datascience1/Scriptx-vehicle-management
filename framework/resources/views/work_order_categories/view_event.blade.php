
<div role="tabpanel">
    <ul class="nav nav-pills">
        <li class="nav-item" style="margin-bottom: 20px"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active"> @lang('fleet.general_info') <i class="fa"></i></a>
        </li>

        <li class="nav-item"><a href="#prev-tab" data-toggle="tab" class="nav-link custom_padding"> Attendance History <i class="fa"></i></a>
        </li>
    </ul>

	<div class="tab-content">
		<!-- tab1-->
		<div class="tab-pane active" id="info-tab">
			<table class="table table-striped">
				<tr>
					<th>Driver Name</th>
					<td>{{$leave->driver->name}}</td>
				</tr>

				<tr>
					<th>Date</th>
					<td>
						{{Helper::getCanonicalDate($leave->date)}}
					</td>
				</tr>

				<tr>
					<th>Status</th>
					<td>
						@if($leave->is_present==1)
                            <span class="badge badge-success">Present</span>
                        @elseif($leave->is_present==2)
                            <span class="badge badge-danger">Absent</span>
						@elseif($leave->is_present==3)
							<span class="badge badge-info">1st Half Leave</span>
						@elseif($leave->is_present==4)
							<span class="badge badge-primary">2nd Half Leave</span>
                        @elseif($leave->is_present==null)
                            <span class="badge badge-warning">N/A</span>
                        @endif
					</td>
				</tr>

				<tr>
					<th>Remarks</th>
					<td>
						{{$leave->remarks!="" ? $leave->remarks : "N/A"}}
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
					<th>Status</th>
                    <th>Remarks</th>
				</tr>
                @foreach($historys as $hist)
                    <tr>
                        <th>{{Helper::getCanonicalDate($hist->date)}}</th>
                        <td>
                            @if($hist->is_present==1)
                            <span class="badge badge-success">Present</span>
							@elseif($hist->is_present==2)
								<span class="badge badge-danger">Absent</span>
							@elseif($hist->is_present==3)
								<span class="badge badge-info">1st Half Leave</span>
							@elseif($hist->is_present==4)
								<span class="badge badge-primary">2nd Half Leave</span>
							@elseif($hist->is_present==null)
								<span class="badge badge-warning">N/A</span>
							@endif
                        </td>
                        <td>
                            {{$hist->remarks}}
                        </td>
                    </tr>
                @endforeach
			</table>
			
		</div>
</div>
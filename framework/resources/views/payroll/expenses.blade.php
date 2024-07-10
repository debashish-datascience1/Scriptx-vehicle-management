{{-- @if(is_array($help) && count($help)>0) --}}

    @if($isAlreayPaid) {{-- check if already paid --}}
        <div class="col-md-12">
            <div class="form-group">
            <span style="color: green"> <i class="fa fa-check"></i>   Salary of {{$userData->name}} for the month of {{$imonth}} is already paid</span>
            </div>
        </div> 
    @elseif(!$isLeaveChecked)
        <div class="col-md-12">
            <div class="form-group">
            <span style="color: red"><i>Please provide leave details of {{$userData->name}} for the month of {{$imonth}} to proceed. You can add leave details <a href="{{route("bulk_leave.create")}}" target="_blank">here</a></i></span>
            </div>
        </div> 
    @elseif(!empty($yetToComplete))
        <div class="col-md-12">
            <div class="form-group">
            <span style="color: red"><i><b>Mark as Complete</b> {{$yetToComplete->count()}} bookings of {{$userData->name}} for the month of {{$imonth}} to proceed. You can complete them <a href="{{route("bookings.index")}}" target="_blank">here</a></i></span>
            </div>
        </div>
        <div class="col-md-12 p-3">
            <div class="form-group">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Transaction ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($yetToComplete as $k=>$y)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{!empty(Helper::getTransaction($y->id,18)) ? Helper::getTransaction($y->id,18)->transaction_id : '-'}}</td>
                            <td>{{Helper::getCanonicalDateTime($y->pickup,'default')}}</td>
                            <td>{{Hyvikk::get('currency')}} {{bcdiv($y->getMeta('advance_pay'),1,2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
            @if(count($advanceFromBooking)>0 || $salary_advance>0)
            <div class="col-md-12">
                <div class="form-group">
                <span style="color: green"><i>Driver doesn't have any booking with driver advance that is yet to be <b>Mark as Complete</b> for the month of {{$imonth}}</i></span>
                </div>
            </div>
            @endif
            @if(count($advanceFromBooking)>0)
            <div class="col-md-12 p-3">
                <div class="form-group">
                    <h6><strong> Booking (Advances to Driver) [{{$imonth}}]</strong></h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SL#</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advanceFromBooking as $k=>$y)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{!empty(Helper::getTransaction($y->id,18)) ? Helper::getTransaction($y->id,18)->transaction_id : '-'}}</td>
                                <td>{{Helper::getCanonicalDateTime($y->pickup,'default')}}</td>
                                <td>{{Hyvikk::get('currency')}} {{$y->advanceToDriver->value}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if($salary_advance>0)
            <div class="col-md-12 p-3">
                <div class="form-group">
                    <h6><strong> Salary Advance ({{$imonth}})</strong></h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>SL#</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salary_details as $k=>$y)
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{Helper::getTransaction($y->id,25)->transaction_id}}</td>
                                <td>{{Helper::getCanonicalDate($y->date,'default')}}</td>
                                <td>{{Hyvikk::get('currency')}} {{$y->amount}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @if(count($advanceFromBooking)==0 && $salary_advance==0)
            <div class="col-md-12">
                <div class="form-group">
                <span style="color: green"><i>Driver didn't get any advances from <b>Bookings</b> for the month of {{$imonth}}</i></span>
                </div>
            </div>
            @endif
        @endif
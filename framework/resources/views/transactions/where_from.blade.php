<div class="modal-header">
    <h4 class="modal-title">{{ucwords($transaction->params->label)}} Details</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    {{-- Booking Details Starts --}}
    @if($transaction->param_id==18)
        <div role="tabpanel" style="margin-bottom: 10px;">
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="#info-tab" data-toggle="tab" class="nav-link custom_padding active" style="margin-bottom: 10px;"> General Information <i class="fa"></i></a>
                </li>

                <li class="nav-item"><a href="#load-tab" data-toggle="tab" class="nav-link custom_padding"> Load Details <i class="fa"></i></a>
                </li>

                <li class="nav-item"><a href="#journey-tab" data-toggle="tab" class="nav-link custom_padding"> Journey Details <i class="fa"></i></a>
                </li>
            </ul>

            <div class="tab-content">
            <!-- General Information Tab-->
                <div class="tab-pane active" id="info-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Customer </th>
                            <td>{{$data->customer->name}}</td>
                        </tr>
                        <tr>
                            <th>Vehicle </th>
                            <td>{{$data->vehicle->make}} - {{$data->vehicle->model}} - {{$data->vehicle->license_plate}}</td>
                        </tr>
                        <tr>
                            <th>Driver </th>
                            <td>{{$data->driver->name}}</td>
                        </tr>
                        <tr>
                            <th>Pickup Address</th>
                            <td>{{$data->pickup_addr}}</td>
                        </tr>
                        <tr>
                            <th>Pickup Date & Time</th>
                            <td>{{Helper::getCanonicalDate($data->pickup,'default')}} {{date("g:i:s A",strtotime($data->pickup))}}</td>
                        </tr>
                        <tr>
                            <th>Dropoff Address</th>
                            <td>{{$data->dest_addr}}</td>
                        </tr>
                        <tr>
                            <th>Dropoff Date & Time</th>
                            <td>{{Helper::getCanonicalDate($data->dropoff,'default')}} {{date("g:i:s A",strtotime($data->dropoff))}}</td>
                        </tr>
                        <tr>
                            <th>Party Name</th>
                            <td>{{$data->getMeta('party_name')}}</td>
                        </tr>
                        <tr>
                            <th>Narration</th>
                            <td>{{$data->getMeta('narration')}}</td>
                        </tr>
                        @if($data->status==1)
                        <tr>
                            <th>Booking Status</th>
                            <td><span class="badge badge-success">Completed</span></td>
                        </tr>
                        @endif
                    </table>
                </div>
                <!-- Load Tab-->
                <div class="tab-pane" id="load-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Load Price</th>
                            <td><span class="fa fa-inr"></span> {{$data->getMeta('loadprice')}} per {{$params->label=='Quantity' ? 'Quintals' : $params->label}}</td>
                        </tr>
                        <tr>
                            <th>Load Quantity</th>
                            <td>{{$data->getMeta('loadqty')}} {{$params->label=='Quantity' ? 'Quintals' : $params->label}}</td>
                        </tr>
                        <tr>
                            <th>Fuel Per Litre</th>
                            <td><span class="fa fa-inr"></span> {{$data->getMeta('perltr')}}</td>
                        </tr>
                        <tr>
                            <th>Material</th>
                            <td>{{$data->getMeta('material')}}</td>
                        </tr>
                    </table>
                </div>
                <!-- Journey Tab-->
                <div class="tab-pane" id="journey-tab">
                    <table class="table table-striped">
                        <tr>
                            <th>Initial KM. on Vehicle</th>
                            <td>{{$data->getMeta('initial_km')}} {{Hyvikk::get('dis_format')}}</td>
                        </tr>
                        <tr>
                            <th>Distance</th>
                            <td>{{$data->getMeta('distance')}}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{$data->getMeta('duration_map')}}</td>
                        </tr>
                        <tr>
                            <th>Vehicle Mileage</th>
                            <td>{{$data->getMeta('mileage')}} km/ltr</td>
                        </tr>
                        <tr>
                            <th>Fuel Required(ltr)</th>
                            <td>{{$data->getMeta('pet_required')}} litre</td>
                        </tr>
                        <tr>
                            <th>Fuel Per Litre</th>
                            <td> <span class="fa fa-inr"></span> {{$data->getMeta('perltr')}}</td>
                        </tr>
                        <tr>
                            <th>Total Fuel Price</th>
                            <td><span class="fa fa-inr"></span>  {{$data->getMeta('petrol_price')}}</td>
                        </tr>
                        <tr>
                            <th>Total Freight Price</th>
                            <td><span class="fa fa-inr"></span>  {{$data->getMeta('total_price')}}</td>
                        </tr>
                        @if ($data->getMeta('old_frieght_price'))
                        <tr>
                            <th>Old Freight Price</th>
                            <td>
                                <span class="fa fa-inr"></span>  {{$data->getMeta('old_frieght_price')}}
                            </td>
                        </tr>
                        @endif
                        @if (!empty($from_transa))
                        <tr>
                            <th>Non Payable Amount</th>
                            <td>
                                <span class="fa fa-inr"></span>  {{$from_transa->total}}<br>
                                <i>Ref. ID.</i><span class="badge badge-info">{{$from_transa->transaction_id}}</span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Advance to Driver</th>
                            <td>
                                @if($data->getMeta('advance_pay')!='')
                                    <span class="fa fa-inr"></span> {{$data->getMeta('advance_pay')}}
                                @else
                                    <span class="badge badge-warning"><i>No Advance was given...</i></span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ride Status</th>
                            <td>
                                @php $status_arr = ['upcoming'=>'warning','completed'=>'success','cancelled'=>'danger'] @endphp
                                <span class="badge badge-{{$status_arr[strtolower($data->getMeta('ride_status'))]}}">{{$data->getMeta('ride_status')}}</span>
                            </td>
                        </tr>
                    </table>
                </div>  
            </div>
        </div>
    @endif
    {{-- Booking details ends --}}
    
    {{-- Payroll details Start --}}
    @if($transaction->param_id==19)
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
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
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>
                    @if(!empty($payroll->driver_vehicle))
                        {{$payroll->driver_vehicle->vehicle->make}}-{{$payroll->driver_vehicle->vehicle->model}}-<strong>{{$payroll->driver_vehicle->vehicle->license_plate}}</strong>
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
                    <strong>{{date("m-Y",strtotime($payroll->for_year."-".$month."-01"))}}/
                    {{date("F-Y",strtotime($payroll->for_year."-".$month."-01"))}}</strong>
                </td>
            </tr>
            <tr>
                <th>Working Days</th>
                <td>
                    {{$payroll->working_days!='' ? $payroll->working_days :'0'}} days
                </td>
            </tr>
            <tr>
                <th>Absent Days</th>
                <td>
                    {{$payroll->absent_days!='' ? $payroll->absent_days :'0'}} days
                </td>
            </tr>
            <tr>
                <th>Monthly Salary</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Deducted Salary</th>
				<td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->deduct_salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Total Payable Salary</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->total_payable_salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Carried Salary</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->carried_salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Paid Salary</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->payable_salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Remaining Salary</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->remaining_salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Salary Advance </th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->advance_salary,1,2)}}</td>
            </tr>
            <tr>
                <th>Booking Advance </th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($payroll->advance_driver,1,2)}}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>
                    {{$payroll->payroll_remarks}}
                </td>
            </tr>
        </table>
    @endif
    {{-- Payroll details Ends --}}

    {{-- Fuel Starts --}}
    @if($transaction->param_id==20)
        <table class="table table-striped">
            <tr>
                <th>Vehicle</th>
                <td>{{$fuel->vehicle_data->make}} - {{$fuel->vehicle_data->model}} - <strong>{{$fuel->vehicle_data->license_plate}}</strong></td>
            </tr>
            <tr>
                <th>Vendor</th>
                <td>{{$fuel->vendor->name}}</td>
            </tr>
            <tr>
                <th>Province</th>
                <td>{{$fuel->province}}</td>
            </tr>
            <tr>
                <th>Fuel</th>
                <td>{{$fuel->fuel_details->fuel_name}}</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{{$transaction->income_expense->method->label}}</td>
            </tr>
            <tr>
                <th>Cost per Unit</th>
                <td>{{Hyvikk::get('currency')}} {{number_format($fuel->cost_per_unit,2,'.','')}}</td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td>{{$fuel->qty}} ltr</td>
            </tr>
            <tr>
                <th>Total</th>
                <td>{{Hyvikk::get('currency')}} {{number_format($fuel->qty * $fuel->cost_per_unit,2,'.','')}}</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>{{Helper::getCanonicalDate($fuel->date,'default')}}</td>
            </tr>
            {{-- <tr>
                <th>Complete</th>
                <td>
                    @if($fuel->complete)
                        <span class="badge badge-success">Completed</span>
                    @else
                        <span class="badge badge-danger">Incompleted</span>
                    @endif
                </td>
            </tr> --}}
        </table>
    @endif
    {{-- Fuel Ends --}}

    {{-- Daily Advance Starts --}}
    @if($transaction->param_id==25)
        <table class="table table-striped">
        @if(!empty($advance->driver))
            <tr>
                <th>Driver Name</th>
                <td><strong>{{$advance->driver->name}}</strong></td>
            </tr>

            <tr>
                <th>Date</th>
                <td>
                    {{Helper::getCanonicalDate($advance->date,'default')}}
                </td>
            </tr>

            <tr>
                <th>Payment Method</th>
                <td>
                    {{$transaction->income_expense->method->label}}
                </td>
            </tr>

            <tr>
                <th>Amount <span class="fa fa-inr"></span></th>
                <td>
                    <strong>{{Hyvikk::get('currency')}} {{number_format($advance->amount,2,'.','')}}</strong>
                </td>
            </tr>

            <tr>
                <th>Payroll </th>
                <td>
                    @if($advance->payroll_check==1)
                        <span class="badge badge-success"><i class="fa fa-check"></i> Checked</span>
                    @else
                        <span class="badge badge-danger"><i class="fa fa-times"></i> Not Checked</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td>
                    {{$advance->remarks!="" ? $advance->remarks : "N/A"}}
                </td>
            </tr>
        @else
            <tr>
                <th align="center" style="color:red;">Driver doesn't exist...</th>
            </tr>
        @endif
        </table>

    @endif
    {{-- Daily Advance ends --}}
    
    {{-- Parts  Starts--}}
    @if($transaction->param_id==26)
        <table class="table table-striped" >
            <tr>
                <th colspan="8" class="text-center">
                    @if(!$parts->isEmpty())
                        <span style="font-size: 20px;display: block;">{{$parts->first()->parts_invoice->billno}}</span>
                        <span>{{$parts->first()->parts_invoice->vendor->name}}</span>
                    @else
                        No Parts Found..
                    @endif
                </th>
            </tr>
            <tr>
                <th>Part</th>
                <th>Category</th> 
                <th>@lang('fleet.manufacturer')</th>
                <th>@lang('fleet.availability')</th>
                <th>@lang('fleet.unit_cost')</th>
                <th>@lang('fleet.qty_on_hand')</th>
                <th>@lang('fleet.total')</th>
            </tr>
            @foreach($parts as $dat) 
            <tr>
                <td> {{$dat->parts_zero->item}}</td>
                <td>{{$dat->category->name}}</td> 
                <td>{{$dat->parts_zero->manufacturer_details->name}}</td>
                <td>
                @if($dat->availability == 1)
                    @lang('fleet.available')
                @else
                    @lang('fleet.not_available')
                @endif
                </td>
                <td>{{Hyvikk::get('currency')." ". $dat->unit_cost}}</td>
                <td>{{$dat->quantity}}</td>
                <td>{{Hyvikk::get('currency')." ". $dat->total}}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="5"></th>
                <th>Total</th>
                <th>{{Hyvikk::get('currency')}} {{$parts->sum('total')}}</th>
            </tr>
            @if($parts->first()->parts_invoice->cgst_amt!="")
            <tr>
                <th colspan="4"></th>
                <th>CGST %</th>
                <th style="font-style: italic;">@ {{$parts->first()->parts_invoice->cgst}} %</th>
                <th>{{Hyvikk::get('currency')}} {{$parts->first()->parts_invoice->cgst_amt}}</th>
            </tr>
            @endif
            @if($parts->first()->parts_invoice->sgst_amt!="")
            <tr>
                <th colspan="4"></th>
                <th>SGST %</th>
                <th style="font-style: italic;">@ {{$parts->first()->parts_invoice->sgst}} %</th>
                <th>{{Hyvikk::get('currency')}} {{$parts->first()->parts_invoice->sgst_amt}}</th>
            </tr>
            @endif
            <tr>
                <th colspan="4"></th>
                <th colspan="2">Grand Total</th>
                <th>{{Hyvikk::get('currency')}} {{$parts->first()->parts_invoice->grand_total}}</th>
            </tr>
        </table>
    @endif
    {{-- Part Ends --}}

    {{-- Refund Starts --}}
    @if($transaction->param_id==27)
        <table class="table table-striped">
            @if($advances->count()>0)
                @foreach($advances as $advance)
                    <tr class="{{$advance->param_id==9 && $advance->value!='' ? 'border-refund' : ''}}">
                        <th>{{$advance->param_name->label}}</th>
                        <td>
                            @if($advance->value!='')
                                {{Hyvikk::get('currency')}} {{$advance->value}}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{$advance->remarks}}</td>
                    </tr>
                @endforeach
                <tr style="border-top:2px solid #4fb765;">
                    <th>Grand Total Advance</th>
                    <th>
                        {{Hyvikk::get('currency')}}{{$advTotal}}
                    </th>
                    <th></th>
                </tr>
            @else
                <tr>
                    <td colspan="2" align="center" style="color: red"><i>No Advances were given in this booking...</i></td>
                </tr>
            @endif
        </table>
    @endif
    {{-- Refund Ends --}}

    {{-- Work Order Starts --}}
    @if($transaction->param_id==28)
        @foreach($workOrders as $workOrder)
        <table class="table table-striped">
            @if(!empty($workOrder->created_on))
            <tr>
                <th>Created On :</th>
                <td>{{$workOrder->created_on}}</td>
            </tr>
            @endif
            <tr>
                <th>Required By :</th>
                <td>{{Helper::getCanonicalDate($workOrder->required_by,'default')}}</td>
            </tr>
            <tr>
                <th>Vehicle :</th>
                <td>{{$workOrder->vehicle->make}} - {{$workOrder->vehicle->model}} - <strong>{{$workOrder->vehicle->license_plate}}</strong></td>
            </tr>
            <tr>
                <th>Vendor :</th>
                <td>{{$workOrder->vendor->name}}</td>
            </tr>
            <tr>
                <th>Price :</th>
                <td>{{Hyvikk::get('currency')}} {{number_format($workOrder->price,2,'.','')}}</td>
            </tr>
             <tr>
                <th>Status :</th>
                <td>{{$workOrder->status}}</td>
            </tr>
            <tr>
                <th>Description :</th>
                <td>{{$workOrder->description}}</td>
            </tr>
            <tr>
                <th>Meter :</th>
                <td>{{$workOrder->meter}}</td>
            </tr>
            <tr>
                <th>Note :</th>
                <td>{{$workOrder->note}}</td>
            </tr>
        </table>
        @endforeach
    @endif
    {{-- Work Order Ends --}}

    {{-- Bank Starting Amount Starts --}}
    @if($transaction->param_id==29)
        @foreach($bankAccounts as $bankAccount)
        <table class="table table-striped">
            <tr>
                <th style="width: 145px;">Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
            <tr>
                <th>Bank</th>
                <td>{{$bankAccount->bank}}</td>
            </tr>
            <tr>
                <th>Account No.</th>
                <td>{{$bankAccount->account_no}}</td>
            </tr>
            <tr>
                <th>IFSC Code</th>
                <td>{{$bankAccount->ifsc_code}}</td>
            </tr>
            <tr>
                <th>Branch</th>
                <td>{{$bankAccount->branch}}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{$bankAccount->account_holder}}</td>
            </tr>
            <tr>
                <th>Starting Amount</th>
                <td><strong>{{Hyvikk::get('currency')}} {{number_format($bankAccount->starting_amount,2,'.','')}}</strong></td>
            </tr>
            <tr>
                <th>Account Holder</th>
                <td>{{$bankAccount->account_holder}}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>{{$bankAccount->address}}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{$bankAccount->mobile}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$bankAccount->email}}</td>
            </tr>
        </table>
        @endforeach
    @endif
    {{-- Bank Starting Amount Ends --}}

    {{-- Deposit Amount Starts --}}
    @if($transaction->param_id==30)
        @foreach($bankTransactions as $deposit)
        <table class="table table-striped">
            <tr>
                <th style="width: 130px;">Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
            <tr>
                <th>To</th>
                <td><strong>{{$deposit->bank->bank}}</strong></td>
            </tr>
            @if(!empty($deposit->refer_bank))
            <tr>
                <th>From</th>
                <td><strong>{{$deposit->referBank->bank}}</strong></td>
            </tr>
            @endif
            <tr>
                <th>Date</th>
                <td>{{Helper::getCanonicalDate($deposit->date,'default')}}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>{{Helper::properDecimals($deposit->amount)}}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{$deposit->remarks}}</td>
            </tr>
        </table>
        @endforeach
    @endif
    {{-- Bank Starting Amount Ends --}}

    {{-- Revised Rate Starts --}}
    @if($transaction->param_id==31)
        @foreach($bankTransactions as $deposit)
        <table class="table table-striped">
            <tr>
                <th style="width: 130px;">Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
            <tr>
                <th>To</th>
                <td><strong>{{$deposit->bank->bank}}</strong></td>
            </tr>
            @if(!empty($deposit->refer_bank))
            <tr>
                <th>From</th>
                <td><strong>{{$deposit->referBank->bank}}</strong></td>
            </tr>
            @endif
            <tr>
                <th>Date</th>
                <td>{{Helper::getCanonicalDate($deposit->date,'default')}}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>{{Helper::properDecimals($deposit->amount)}}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{$deposit->remarks}}</td>
            </tr>
        </table>
        @endforeach
    @endif
    {{-- Revised Rate Ends --}}

    {{-- Liability Starts --}}
    @if($transaction->param_id==32)
        <table class="table table-striped">
        @if(!empty($advance->driver))
            <tr>
                <th>Driver Name</th>
                <td><strong>{{$advance->driver->name}}</strong></td>
            </tr>

            <tr>
                <th>Date</th>
                <td>
                    {{Helper::getCanonicalDate($advance->date,'default')}}
                </td>
            </tr>

            <tr>
                <th>Payment Method</th>
                <td>
                    {{$transaction->income_expense->method->label}}
                </td>
            </tr>

            <tr>
                <th>Amount <span class="fa fa-inr"></span></th>
                <td>
                    <strong>{{Hyvikk::get('currency')}} {{$advance->amount}}</strong>
                </td>
            </tr>

            <tr>
                <th>Payroll </th>
                <td>
                    @if($advance->payroll_check==1)
                        <span class="badge badge-success"><i class="fa fa-check"></i> Checked</span>
                    @else
                        <span class="badge badge-danger"><i class="fa fa-times"></i> Not Checked</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td>
                    {{$advance->remarks!="" ? $advance->remarks : "N/A"}}
                </td>
            </tr>
        @else
            <tr>
                <th align="center" style="color:red;">Driver doesn't exist...</th>
            </tr>
        @endif
        </table>

    @endif
    {{-- Liability Ends --}}
    
    {{-- Document Renewal Starts --}}
    @if($transaction->param_id==35)
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td>{{$row->transaction->transaction_id}}</td>
            </tr>
            <tr>
                <th>Document</th>
                <td>{{$row->document->label}}</td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>{{$row->vehicle->make}} - {{$row->vehicle->model}} - <label>{{$row->vehicle->license_plate}}</label></td>
            </tr>
            <tr>
                <th>Driver</th>
                <td>
                    @if(!empty($row->driver_id) && !empty($row->drivervehicle) && !empty($row->drivervehicle->assigned_driver))
                        {{$row->drivervehicle->assigned_driver->name}}
                    @else
                    <span style="color: red"><small><i>Driver not assigned</i></small></span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>On Date</th>
                <td>
                    {{Helper::getCanonicalDate($row->date,'default')}}
                </td>
            </tr>
            <tr>
                <th>Valid Till</th>
                <td>
                    {{Helper::getCanonicalDate($row->till,'default')}}<br>
                    @php   ($to = \Carbon\Carbon::now())

                    @php ($from = \Carbon\Carbon::createFromFormat('Y-m-d', $row->till))

                    @php ($diff_in_days = $to->diffInDays($from))
                    <label>@lang('fleet.after') {{$diff_in_days}} @lang('fleet.days')</label>
                </td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>{{Hyvikk::get('currency')}} {{Helper::properDecimals($row->amount)}}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($row->status==1)
                    <span class="text-success">Completed</span>
                    @else
                    <span class="text-warning">In Progress</span>
                    @endif
                </td>
            </tr>

            <tr>
                <th>Remarks</th>
                <td>
                    {{$row->remarks!="" ? $row->remarks : "N/A"}}
                </td>
            </tr>
        </table>
    @endif
    {{-- Document Renewal ends --}}

    {{-- Other Advance Starts --}}
    @if($transaction->param_id==43)
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
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
                <td>{{Helper::getCanonicalDateTime($adv->created_at,'default')}}</td>
            </tr>
        </table>
    @endif
    {{-- Other Advance Ends --}}

    {{-- Advance Refund Starts --}}
    @if($transaction->param_id==44)
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
            <tr>
                <th>Head</th>
                <th>{{$oth->head}}</th>
            </tr>
            <tr>
                <th>Amount</th>
                <td>
                    {{Hyvikk::get('currency')}} {{Helper::properDecimals($oth->amount)}}
                    <br>
                    @if($oth->type==23)
                        <span class="badge badge-success">{{$oth->payment_type->label}}</span>
                    @elseif($oth->type==24)
                        <span class="badge badge-danger">{{$oth->payment_type->label}}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Method</th>
                <td>{{$oth->method_param->label}}</td>
            </tr>
            <tr>
                <th>Ref. No.</th>
                <td>{{$oth->ref_no}}</td>
            </tr>
            @if($oth->bank_id!='')
            <tr>
                <th>Bank</th>
                <td>{{$oth->bank_details->bank}}({{$oth->bank_details->account_no}})</td>
            </tr>
            @endif
            <tr>
                <th>Date</th>
                <td>{{Helper::getCanonicalDate($oth->date,'default')}}</td>
            </tr>
            <tr>
                <th>Remarks</th>
                <td>{{$oth->remarks}}</td>
            </tr>
            <tr>
                <th>Created On</th>
                <td>{{Helper::getCanonicalDateTime($oth->created_at,'default')}}</td>
            </tr>
        </table>
    @endif
    {{-- Advance Refund Ends --}}

    {{-- Down Payment Starts --}}
    @if($transaction->param_id==49)
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td>{{$purchInfo->vehicle->make}}-{{$purchInfo->vehicle->model}}-<strong>{{$purchInfo->vehicle->license_plate}}</strong></td>
            </tr>
            <tr>
                <th>Purchase Date</th>
                <td>{{Helper::getCanonicalDate($purchInfo->purchase_date,'default')}}</td>
            </tr>
            <tr>
                <th>Loan Date</th>
                <td>{{Helper::getCanonicalDate($purchInfo->loan_date,'default')}}</td>
            </tr>
            <tr>
                <th>EMI Date</th>
                <td>{{Helper::getCanonicalDate($purchInfo->emi_date,'default')}}</td>
            </tr>
            <tr>
                <th>Vehicle Cost</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($purchInfo->vehicle_cost,1,2)}}</td>
            </tr>
            <tr>
                <th>Down Payment</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($purchInfo->amount_paid,1,2)}}</td>
            </tr>
            <tr>
                <th>Loan Amount</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($purchInfo->loan_amount,1,2)}}</td>
            </tr>
            <tr>
                <th>EMI Amount</th>
                <td>{{Hyvikk::get('currency')}} {{bcdiv($purchInfo->emi_amount,1,2)}} <small><i> /per month</i></small></td>
            </tr>
            <tr>
                <th>Loan Duration</th>
                <td>
                    {{$purchInfo->loan_duration}}
                    @if ($purchInfo->duration_unit!=null)
                        {{$purchInfo->duration_unit}}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Bank</th>
                <td>{{$purchInfo->bank_details->bank}}</td>
            </tr>
            <tr>
                <th>Method</th>
                <td>{{$purchInfo->method_details->label}}</td>
            </tr>
            <tr>
                <th>Reference No.</th>
                <td>{{$purchInfo->reference_no}}</td>
            </tr>
            
        </table>
    @endif
    {{-- Down Payment Ends --}}

    {{-- EMI Starts --}}
    @if($transaction->param_id==50)
        <table class="table table-striped">
            <tr>
                <th>Transaction ID</th>
                <td>{{$transaction->transaction_id}}</td>
            </tr>
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
    @endif
    {{-- EMI Ends --}}
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')
    </button>
</div>
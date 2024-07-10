@php
    use App\Model\VehicleDocs;
@endphp
@foreach($vehicles as $v)
<div class="row">
    {{-- <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('vehicle_id',__('fleet.select_vehicle'), ['class' => 'form-label']) !!}
            <label for="">{{$v->make}}-{{$v->license_plate}}</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::text('date',null,['class' => 'form-control date','required','readonly']) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::text('amount',null,['class' => 'form-control amount','required']) !!}
        </div>
    </div> --}}
    <div class="col-md-12">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td align="center" style="font-size:23px;">
                            <strong>{{$v->make}}-{{$v->model}}-{{$v->license_plate}}</strong>
                            @if(!empty($v->driver) && !empty($v->driver->assigned_driver))
                            <br><span>{{ucwords(strtolower($v->driver->assigned_driver->name))}}</span>
                            @else
                            <br>
                            <span style="font-size:15px;font-style:italic">Driver not assigned</span>
                            @endif
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Documents</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Bank & Method</th>
                                        <th width="25%">Reference No.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($docparams as  $doc)
                                    @php

                                    
                                    $docName = $docparamArray[$doc->id];
                                    //checking last day
                                    if(!empty($v->getMeta($docName[2]))){
                                            $expirationDateSet = true;
                                    
                                        //  checking if amount and duration is set
                                        //36-insurance,37-fitness,38-roadtax,39-permit,40-pollution
                                        //index 0-duration,1-amount,2-expiration date
                                        if(!empty($v->getMeta($docName[0])) && !empty($v->getMeta($docName[1]))){
                                            $durationUnitSet = true;
                                            //check how many days document have to expire
                                            $daysLeft = Helper::renewLastday($v->getMeta($docName[2]));
                                            if($daysLeft<=0){
                                                $lastDay = true;
                                                $vehicleDoc = VehicleDocs::where(['vehicle_id'=>$v->id,'param_id'=>$doc->id])->orderBy('id','DESC');
                                                if($vehicleDoc->exists()){
                                                    $vehicleTillDate = $vehicleDoc->first()->till;
                                                    $daysLeft = Helper::renewLastday($vehicleTillDate);
                                                    $lastDay = $daysLeft<=0 ? true :false;
                                                }
                                            }else{
                                                $lastDay = false;
                                            }
                                        }else{
                                            $durationUnitSet = false;
                                        }
                                    }else{
                                        $expirationDateSet = false;
                                    }
                                    
                                    
                                    @endphp
                                    <tr>
                                        <td>
                                            {!! Form::label("$doc->label","$doc->label", ['class' => 'form-label']) !!}
                                            {{-- {{$doc->id == 38 ? dd($v->getMeta($docName)) : 'sdjlkakljd'}} --}}
                                        </td>
                                        @if($expirationDateSet)
                                        @if($durationUnitSet)
                                        @if($lastDay)
                                        <td>
                                            {!! Form::text("date[$v->id][$doc->id]",null,['class' => 'form-control date','required','readonly','data-id'=>$v->id,'data-doc'=>$doc->id]) !!}
                                            {{-- <input type="hidden" name="hid[{{$v->id}}]" value="{{$v->id}}">    --}}
                                        </td>
                                        <td>
                                            {!! Form::text("amount[$v->id][$doc->id]",null,['class' => 'form-control amount','required','onkeypress'=>'return isNumber(event,this)','placeholder'=>'Enter Renewal Amount']) !!}
                                            {!! Form::select("vendor[$v->id][$doc->id]",$vendors,null,['class' => 'form-control vendor','required','placeholder'=>'Select Vendor','style'=>'margin-top:10px;']) !!}
                                        </td>
                                        <td>
                                            {{-- {!! Form::select("status[$v->id][$doc->id]",[1=>'Complete','2'=>'In Progress'],null,['class' => 'form-control status','required','placeholder'=>'Select']) !!} --}}
                                            {!! Form::select("bank[$v->id][$doc->id]",$bankAccount,null,['class' => 'form-control bank','required','placeholder'=>'Select Bank','style'=>'margin-top:10px;']) !!}
                                            {!! Form::select("method[$v->id][$doc->id]",$method,null,['class' => 'form-control method','required','placeholder'=>'Select Method','style'=>'margin-top:10px;']) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("ddno[$v->id][$doc->id]",null,['class' => 'form-control ddno','required','style'=>'margin-top: 9px;','placeholder'=>'Reference No.']) !!}
                                            {!! Form::textarea("remarks[$v->id][$doc->id]",null,['class' => 'form-control remarks','style'=>'height:100px;resize:none;margin-top:7px;','placeholder'=>'Remarks (if any)']) !!}
                                        </td>
                                        <td>
                                            <input type="button" value="Renew" class="btn btn-success renew_btn">
                                            <input type="hidden" name="hiddenInput" class="singleVehicleId" value="{{$v->id}}">
                                        </td>
                                        @else
                                        {{-- {{dd($lastDay,$tillDate)}} --}}
                                         <td colspan="5" align="center">
                                            @lang('fleet.after') {{$daysLeft}} @lang('fleet.days')
                                         </td>
                                        @endif
                                        @else
                                        {{-- {{dd($lastDay,$tillDate)}} --}}
                                         <td colspan="5" align="center">
                                            {{-- @lang('fleet.after') {{$daysLeft}} @lang('fleet.days') --}}
                                            <label for="">{{$doc->label}} duration and unit is not set</label> <a href="../vehicles/{{$v->id}}/edit?tab=insurance" target="_blank">click here</a> to set
                                         </td>
                                        @endif
                                        @else
                                        {{-- {{dd($lastDay,$tillDate)}} --}}
                                         <td colspan="5" align="center">
                                            {{-- @lang('fleet.after') {{$daysLeft}} @lang('fleet.days') --}}
                                            <label for="">{{$doc->label}} expiration date is not set</label> <a href="../vehicles/{{$v->id}}/edit?tab=insurance" target="_blank">click here</a> to set
                                         </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach
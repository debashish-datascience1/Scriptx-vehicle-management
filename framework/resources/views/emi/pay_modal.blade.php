
    <table class="table table-striped table-bordered">
        <tr>
            <th colspan="2" class="text-center">
                <input type="hidden" name="purchase_id" id="purchase_id" value="{{$purchase->id}}">
                <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{$vehicle->id}}">
                <small>Vehicle :</small> {{$vehicle->license_plate}} <br>
                <small>For Month : {{date('m/Y',strtotime($date))}} <i>({{date('F-Y',strtotime($date))}})</i></small>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            {!! Form::label('date', __('fleet.date'), ['class' => 'form-label required']) !!}
                            {!! Form::text('date',Helper::indianDateFormat($date),['class'=>'form-control','readonly','required','id'=>'date']) !!}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label required']) !!}
                            {!! Form::text('amount',$purchase->emi_amount,['class'=>'form-control','readonly','required','id'=>'amount']) !!}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {!! Form::label('pay_date', __('fleet.pay_date'), ['class' => 'form-label required']) !!}
                            {!! Form::text('pay_date',null,['class'=>'form-control','readonly','id'=>'pay_date']) !!}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {!! Form::label('bank', __('fleet.bank'), ['class' => 'form-label required']) !!}
                            {!! Form::select('bank_id',Helper::getBanks(),null,['class'=>'form-control','placeholder'=>'Select Bank','id'=>'bank']) !!}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {!! Form::label('method', __('fleet.method'), ['class' => 'form-label required']) !!}
                            {!! Form::select('method',Helper::getMethods(),null,['class'=>'form-control','placeholder'=>'Select Method','id'=>'method']) !!}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            {!! Form::label('reference_no', __('fleet.reference_no'), ['class' => 'form-label required']) !!}
                            {!! Form::text('reference_no',null,['class'=>'form-control','required','id'=>'reference_no','placeholder'=>'Enter Reference No.']) !!}
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            {!! Form::label('remarks', __('fleet.remarks'), ['class' => 'form-label required']) !!}
                            {!! Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','placeholder'=>'Remarks if any']) !!}
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">{!!Form::submit('Confirm',['class'=>'btn btn-success','id'=>'payEmi'])!!}</td>
        </tr>
    </table>
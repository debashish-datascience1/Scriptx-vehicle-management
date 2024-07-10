@if ($isSalaryPaid)
    <div class="row mb-5">
        <div class="col-md-12 text-center">
            <h5 class="text-danger">Salary is already paid for selected month. Cannot change attendance if salary is paid for selected month</h5>
        </div>
    </div>
@endif
<div class="row">
    @foreach($leaveList as $key=>$leave)
        @php
            $id = !empty($leave) ? $leave->id : null;
            $is_present = !empty($leave) ? $leave->is_present : null;
            $remarks = !empty($leave) ? $leave->remarks : null;
            $style = $isSalaryPaid ? "pointer-events:none;" : "";
            $isReadOnly = $isSalaryPaid ? "readonly" : "";
        @endphp
    <div class="col-md-3">
        <div class="form-group">
        <div class="row">
            <div class="col-md-2 text-center">
                <h4>{{$key+1}}</h4>
                <input type="hidden" name="dates[]" value="{{$key+1}}">
            </div>
            <div class="col-md-10">
            {!! Form::select("attendance[]",Helper::leaveTypes(),$is_present,['class' => 'form-control attendance','id'=>'attendance','placeholder'=>'Select','style'=>$style,$isReadOnly]) !!}
            </div>
            <div class="col-md-12 mt-2">
            {!! Form::textarea("remarks[]",$remarks,['class' => 'form-control remarks','id'=>'remarks','placeholder'=>'Remarks...']) !!}
            </div>
        </div>
        </div>
    </div>
    @endforeach
    <div class="col-md-12">
        {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success','id'=>'btnsubmit']) !!}
    </div>
</div>
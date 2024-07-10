@if(empty($user->getMeta('salary')) || $user->getMeta('salary')==null)
    <span style="color: red;font-style:italic">Edit the salary of driver <strong>{{$user->name}}</strong> to <a href="{{url('admin/drivers/'.$user->id.'/edit/')}}">proceed...</a></span>
@else
    <div class="container">
        {{-- <form action="" method="POST">
        @csrf @method('POST') --}}
        {!!Form::open(['route'=>'payroll.store'])!!}
        <div class="row">
            {{-- <div class="col-md-12"> --}}
                {{-- <div class="card card-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('drivers', 'Driver Name', ['class' => 'form-label required']) !!}
                                    {!! Form::label('drivers', 'Driver Name', ['class' => 'form-label required']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
            {{-- </div> --}}
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('drivers', 'Driver Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('drivers',$user->name, ['class' => 'form-control','required','readonly']) !!}
                    <input type="hidden" name="driver_id" id="driver_id" value="{{$user->id}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('salary', 'Salary', ['class' => 'form-label required']) !!}
                    {!! Form::text('salary',$user->getMeta('salary'), ['class' => 'form-control','required','readonly','onkeypress'=>'return isNumber(event)']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('month', 'For Month?', ['class' => 'form-label required']) !!}
                    {!! Form::select('month',Helper::getMonths() ,null, ['class' => 'form-control','required','placeholder'=>'Select Month']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('year', 'For Year?', ['class' => 'form-label required']) !!}
                    {!! Form::select('year',Helper::getYear() ,null, ['class' => 'form-control','required','placeholder'=>'Select Year']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('working_days', 'Working Days', ['class' => 'form-label required']) !!}
                    {!! Form::text('working_days',null, ['class' => 'form-control','required','readonly','id'=>'working_days','placeholder'=>'days..']) !!}
                    {{-- <div class="manual_workingdays">
                        <input type="checkbox" name="manual" id="manual" > 
                        <label for="manual">Manual</label>
                    </div> --}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('absent_days', 'Absent Days', ['class' => 'form-label required']) !!}
                    {!! Form::text('absent_days',null, ['class' => 'form-control','required','readonly','id'=>'absent_days','placeholder'=>'days..']) !!}
                    <small id="deduct_sal" style="color: red;"></small>
                    <input type="hidden" name="deduct_salary" value="" id="deduct_salary">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('advance_salary', 'Advance to Salary ?', ['class' => 'form-label required']) !!}
                    {!! Form::text('advance_salary', null, ['class' => 'form-control','required','readonly','id'=>'advance_salary']) !!}
                    <small id="payable_sal" style="color: red;"></small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('advance_driver', 'Advance to Driver ?', ['class' => 'form-label required']) !!}
                    {!! Form::text('advance_driver', null, ['class' => 'form-control','required','readonly','id'=>'advance_driver']) !!}
                    <small id="advancedriver_sal" style="color: red;"></small>
                    {{-- <input type="hidden" name="advance_driver_actual" id="advance_driver_actual"> --}}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('total_payable_salary', 'Total Payable Salary', ['class' => 'form-label required']) !!}
                    {!! Form::text('total_payable_salary', null, ['class' => 'form-control','required','readonly','id'=>'total_payable_salary']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('payable_salary', 'Payable Salary!', ['class' => 'form-label required']) !!}
                    {!! Form::text('payable_salary', null, ['class' => 'form-control','required','readonly','id'=>'payable_salary']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('carried_salary', 'Carried Salary', ['class' => 'form-label required']) !!}
                    {!! Form::text('carried_salary', null, ['class' => 'form-control','required','readonly','id'=>'carried_salary']) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {!!Form::hidden('remaining',base64_encode($remaining),['id'=>'remaining'])!!}
                    {!! Form::label('cash', 'Cash', ['class' => 'form-label required']) !!}
                    {!! Form::text('cash', bcdiv($remaining,1,2), ['class' => 'form-control','required','readonly','id'=>'cash']) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('payroll_remarks', 'Remarks ?', ['class' => 'form-label']) !!}
                    {!! Form::textarea('payroll_remarks', null, ['class' => 'form-control','required','id'=>'payroll_remarks','style'=>'height:75px;resize:none;']) !!}
                </div>
            </div>
        </div>
        <div class="row expenditure_div">
            {{-- Expenditure --}}
        </div>
        <div class="row">
            <div class="col-md-4">
                <input type="submit" value="Add Payroll" id="payroll"  class="btn btn-success" disabled>
            </div>
        </div>
        {!!Form::close()!!}
    </div>
@endif
{!! Form::open(['route' => 'addamount.store','files'=>true,'method'=>'post']) !!}
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!!Form::label('bank','Bank',['class' => 'form-label'])!!}
                {!!Form::select('bank',$banks,null,['class'=>'form-control','id'=>'bank','placeholder'=>'Select Bank','required'])!!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!!Form::label('is_self','Is Self ?',['class' => 'form-label'])!!}
                {!!Form::select('is_self',$is_self,null,['class'=>'form-control','id'=>'is_self','placeholder'=>'Is Self'])!!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!!Form::label('amount','Amount',['class' => 'form-label'])!!}
                {!!Form::text('amount',null,['class'=>'form-control','id'=>'amount','placeholder'=>'Enter Amount','required'])!!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!!Form::label('date','Date',['class' => 'form-label'])!!}
                {!!Form::text('date',null,['class'=>'form-control','id'=>'date','required','readonly'])!!}
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                {!!Form::label('remarks','Remarks',['class' => 'form-label'])!!}
                {!!Form::textarea('remarks',null,['class'=>'form-control','id'=>'remarks','style'=>'height:100px;resize:none;'])!!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::submit('Save', ['class' => 'btn btn-success','id'=>'sub']) !!}
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
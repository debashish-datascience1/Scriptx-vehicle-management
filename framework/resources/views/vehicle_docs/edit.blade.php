@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/jquery-ui/jquery-ui.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item "><a href="{{ route("vehicle-docs.index")}}">Vehicle Documents</a></li>
<li class="breadcrumb-item active">Edit Document</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          Edit Vehicle Document
        </h3>
      </div>

      <div class="card-body">
        @if (count($errors) > 0)
          <div class="alert alert-danger">
            <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
            </ul>
          </div>
        @endif

        {!! Form::open(['route' => ['vehicle-docs.update', $doc->id], 'method'=>'PUT','id'=>'editDocForm','files'=>true]) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        {!! Form::hidden('status',1)!!}
        {!! Form::hidden('doc_id', $doc->param_id)!!}

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('vehicle_id',__('fleet.select_vehicle'), ['class' => 'form-label']) !!}
              {!!Form::select('vehicle_id',$vehicles,$doc->vehicle_id,['class'=>'form-control','id'=>'vehicle_id','required'])!!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('date','Document Date', ['class' => 'form-label']) !!}
              {!! Form::text('date', date('d-m-Y', strtotime($doc->date)), ['class' => 'form-control date', 'id' => 'date', 'required', 'autocomplete' => 'off', 'data-id' => $doc->vehicle_id, 'data-doc' => $doc->param_id]) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('vendor','Vendor', ['class' => 'form-label']) !!}
              {!!Form::select('vendor',$vendors,$doc->vendor_id,['class'=>'form-control vendor','required','placeholder'=>'Select Vendor'])!!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('bank','Bank Account', ['class' => 'form-label']) !!}
              {!!Form::select('bank',$bankAccount,$doc->transaction->bank_id,['class'=>'form-control bank','required','placeholder'=>'Select Bank'])!!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('amount','Amount', ['class' => 'form-label']) !!}
              {!! Form::number('amount', $doc->amount, ['class' => 'form-control amount', 'required', 'step' => '0.01', 'min' => '0']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('method','Payment Method', ['class' => 'form-label']) !!}
              {!!Form::select('method',$method,$doc->method,['class'=>'form-control method','required','placeholder'=>'Select Payment Method'])!!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('ddno','Reference No.', ['class' => 'form-label']) !!}
              {!! Form::text('ddno', $doc->ddno, ['class' => 'form-control ddno', 'required']) !!}
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('remarks','Remarks', ['class' => 'form-label']) !!}
              {!! Form::textarea('remarks', $doc->remarks, ['class' => 'form-control remarks', 'rows' => 2]) !!}
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::submit('Update Document', ['class' => 'btn btn-warning']) !!}
            </div>
          </div>
        </div>

        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{asset('assets/jquery-ui/jquery-ui.min.js')}}"></script>

<script type="text/javascript">
$(document).ready(function() {
   $("body").on("focus",".date",function(){
    var self = $(this);
    $(this).datepicker({ 
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true,
      yearRange: "-70:+0",
      onSelect: function(date){
        var vid = $(this).data("id");
        var ddoc = $(this).data("doc");
        var dataSet = {_token:"{{csrf_token()}}",date:date,vehicle_id:vid,doc_id:ddoc}; 
          $.ajax({
              type:"POST",
              url:"{{route('vehicle-docs.getNext')}}",
              data:dataSet,
              success: function(result){
                if(self.next().length)
                  self.next().remove();
                self.after(result);
              }
          });
      }
    });
   });

   $("#vehicle_id").select2({
     placeholder : 'Please select a vehicle',
   });
});
</script>
@endsection
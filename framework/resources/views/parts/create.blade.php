@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
<style>
  .description{resize: none;height: 120px;}
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route("parts.index")}}">@lang('menu.manageParts')</a></li>
<li class="breadcrumb-item active">@lang('fleet.addParts')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.addParts')</h3>
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

          {!! Form::open(['route' => 'parts.store','method'=>'post','files'=>true]) !!}
          
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('item', __('fleet.item'), ['class' => 'form-label']) !!}
                  {!! Form::text('item', null,['class' => 'form-control item','required','placeholder'=>'Enter Item Name']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('category_id',__('fleet.category'), ['class' => 'form-label']) !!}
                  {!! Form::select("category_id",$categories,null,['class'=>'form-control category_id','id'=>'category_id','placeholder'=>'Select Category','required']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('manufacturer',__('fleet.manufacturer'), ['class' => 'form-label']) !!}
                  {!! Form::select("manufacturer",$manufacturers,null,['class'=>'form-control manufacturer','id'=>'manufacturer','placeholder'=>'Select Vendor','required']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('unit',__('fleet.unit'), ['class' => 'form-label']) !!}
                  {!! Form::select("unit",$units,null,['class'=>'form-control unit','id'=>'unit','placeholder'=>'Select Unit','required']) !!}
                </div>
              </div>
              {{-- <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('hsn', __('fleet.hsn'), ['class' => 'form-label']) !!}
                  {!! Form::text('hsn', null,['class' => 'form-control hsn','required','placeholder'=>'Enter HSN']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('amount', __('fleet.amount'), ['class' => 'form-label']) !!}
                  {!! Form::text('amount', null,['class' => 'form-control amount','required','placeholder'=>'Enter Amount']) !!}
                </div>
              </div> --}}
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('min_stock', __('fleet.min_stock'), ['class' => 'form-label']) !!}
                  {!! Form::text('min_stock', null,['class' => 'form-control min_stock','placeholder'=>'Enter Minimum Stock','onkeypress'=>'return isNumber(event)']) !!}
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('description', __('fleet.description'), ['class' => 'form-label']) !!}
                  {!! Form::textarea('description', null,['class' => 'form-control description','placeholder'=>'Item/Part description..']) !!}
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                  {!! Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success','id'=>'savebtn']) !!}
                </div>
            </div> 
        </div>      
  {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/moment.js') }}"></script>
<script src="{{ asset('assets/js/datetimepicker.js') }}"></script>
<script type="text/javascript">
function select_type(){
    var type=$("#type option:selected").text();
    if(type=="Add New"){
      $("#nothing").empty();
      $("#nothing").html('{!! Form::text('type',null,['class' => 'form-control','required']) !!}');
    }
  }
// Check Number and Decimal
function isNumber(evt, element) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (            
        (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57))
        return false;
        return true;
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


 
</script>
@endsection
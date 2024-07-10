<div class="addmore_cont cal_div" style="width: 100%;">
<hr>
  <div class="" id="parts_form">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
          {!! Form::text('title[]', null,['class' => 'form-control title','required']) !!}
        </div> 
      </div>
      <div class="col-md-6">
          <div class="form-group">
            {!! Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label ']) !!}
            {!! Form::select("category_id[]",$categories,null,['class'=>'form-control category_id','required','placeholder'=>'Select Category']) !!}
          </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']) !!}
          <div class="input-group date">
            <div class="input-group-prepend">
            <span class="input-group-text">{{Hyvikk::get('currency')}}</span> </div>
            {!! Form::text('unit_cost[]', null,['class' => 'form-control unit_cost','required','onkeypress'=>'return isNumber(event,this)']) !!}
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']) !!}
          {!! Form::text('stock[]', null,['class' => 'form-control stock','required','onkeypress'=>'return isNumber(event,this)']) !!}
        </div>
      </div>
      <div class="col-md-4">  
        <div class="form-group">   
          {!! Form::label('total', __('fleet.total'), ['class' => 'form-label']) !!}
          {!! Form::text('total[]', null,['class' => 'form-control total','onkeypress'=>'return isNumber(event,this)']) !!}
        </div>
      </div>
      <div class="row" style="width:100%;margin-bottom:10px;">
        <div class="col-md-12">
          <div class="text-right">
            <button class="btn btn-danger remove" type="button" id="button_removeform" name="button">Remove</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
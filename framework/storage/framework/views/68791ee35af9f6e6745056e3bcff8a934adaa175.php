<div class="addmore_cont cal_div" style="width: 100%;">
<hr>
  <div class="" id="parts_form">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <?php echo Form::label('item', __('fleet.item'), ['class' => 'form-label']); ?>

          <?php echo Form::select('item[]',$items, null,['class' => 'form-control item','required','placeholder'=>'Select Part']); ?>

        </div> 
      </div>
      
      <div class="col-md-4">
        <div class="form-group">
          <?php echo Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']); ?>

          <div class="input-group date">
            <div class="input-group-prepend">
            <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span> </div>
            <?php echo Form::text('unit_cost[]', null,['class' => 'form-control unit_cost','required','onkeypress'=>'return isNumber(event,this)']); ?>

          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <?php echo Form::label('stock', __('fleet.quantity'), ['class' => 'form-label']); ?>

          <?php echo Form::text('stock[]', null,['class' => 'form-control stock','required','onkeypress'=>'return isNumber(event,this)']); ?>

        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <?php echo Form::label('tyre_number', __('fleet.tyre_number'), ['class' => 'form-label']); ?>

          <?php echo Form::text('tyre_number[]', null, ['class' => 'form-control tyre_number', 'disabled' => 'disabled', 'placeholder' => 'Enter comma-separated tyre numbers']); ?>

        </div>
      </div>
      <div class="col-md-4">  
        <div class="form-group">   
          <?php echo Form::label('total', __('fleet.total'), ['class' => 'form-label']); ?>

          <?php echo Form::text('total[]', null,['class' => 'form-control total','onkeypress'=>'return isNumber(event,this)']); ?>

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
</div><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/parts_invoice/parts_form.blade.php ENDPATH**/ ?>
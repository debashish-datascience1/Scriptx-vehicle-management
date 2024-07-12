
<form id="formNewPart">
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <?php echo Form::label('item', __('fleet.item'), ['class' => 'form-label']); ?>

        <?php echo Form::text('item', null,['class' => 'form-control item','required','placeholder'=>'Enter Item Name']); ?>

      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <?php echo Form::label('category_id',__('fleet.category'), ['class' => 'form-label']); ?>

        <?php echo Form::select("category_id",$categories,null,['class'=>'form-control category_id','id'=>'category_id','placeholder'=>'Select Category','required']); ?>

      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <?php echo Form::label('manufacturer',__('fleet.manufacturer'), ['class' => 'form-label']); ?>

        <?php echo Form::select("manufacturer",$manufacturers,null,['class'=>'form-control manufacturer','id'=>'manufacturer','placeholder'=>'Select Vendor','required']); ?>

      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <?php echo Form::label('unit',__('fleet.unit'), ['class' => 'form-label']); ?>

        <?php echo Form::select("unit",$units,null,['class'=>'form-control unit','id'=>'unit','placeholder'=>'Select Unit','required']); ?>

      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <?php echo Form::label('min_stock', __('fleet.min_stock'), ['class' => 'form-label']); ?>

        <?php echo Form::text('min_stock', null,['class' => 'form-control min_stock','placeholder'=>'Enter Minimum Stock','onkeypress'=>'return isNumber(event)']); ?>

      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <?php echo Form::label('description', __('fleet.description'), ['class' => 'form-label']); ?>

        <?php echo Form::textarea('description', null,['class' => 'form-control description','placeholder'=>'Item/Part description..','id'=>'description']); ?>

      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
        <?php echo Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success','id'=>'savebtn']); ?>

      </div>
  </div> 
</form><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/new_part.blade.php ENDPATH**/ ?>
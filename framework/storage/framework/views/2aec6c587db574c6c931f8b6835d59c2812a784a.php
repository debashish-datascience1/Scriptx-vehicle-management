<?php echo Form::open(['route'=>'bookings.lateadvanceSave','method'=>'POST']); ?>

<input type="hidden" name="booking_id" value="<?php echo e($booking_id); ?>">
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="driver_advance">Advance Amount</label>
            <input type="text" class="form-control driver_advance" id="driver_advance" name="driver_advance" required onkeypress="return isNumber(event,this)" placeholder="Enter Amount..">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="advance_date">Payment Date</label>
            <input type="text" class="form-control advance_date" id="advance_date" name="advance_date" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" style="resize: none;height:120px;" placeholder="Enter Remarks.." class="form-control"></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <input type="submit" class="btn btn-success submit" value="Submit" id="submit">
        </div>
    </div>
</div>
<?php echo Form::close(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/bookings/late-advance.blade.php ENDPATH**/ ?>
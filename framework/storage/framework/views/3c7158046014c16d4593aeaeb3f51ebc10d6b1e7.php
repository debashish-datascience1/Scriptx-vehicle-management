<?php echo Form::model($workOrder,['route'=>['work_order.store-order-head',$workOrder->id],'method'=>'PATCH']); ?>

<table class="table table-striped">
    <tr>
        <th>
            <div class="row">
                <div class="col-12 text-center">
                    Tranaction ID : <?php echo e(Helper::getTransaction($workOrder->id,28)->transaction_id); ?><br>
                    <small>Bill No : <?php echo e($workOrder->bill_no); ?></small><br>
                    <small>Date : <?php echo e(Helper::getCanonicalDate($workOrder->required_by,'default')); ?></small>
                </div>
            </div>
        </th>
    </tr>
    <tr>
        <th>
            <?php echo Form::label('category_id', __('fleet.order_head'), ['class' => 'col-xs-5 control-label']); ?>

            <?php echo Form::select('category_id',$workOrderCategory,null,['class' => 'form-control','required','placeholder'=>'Select Order Head']); ?>

        </th>
    </tr>
    <tr>
        <th>
            <?php echo Form::submit('Submit',['class'=>'btn btn-success']); ?>

        </th>
    </tr>
</table>
<?php echo Form::close(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/work_orders/order_head.blade.php ENDPATH**/ ?>
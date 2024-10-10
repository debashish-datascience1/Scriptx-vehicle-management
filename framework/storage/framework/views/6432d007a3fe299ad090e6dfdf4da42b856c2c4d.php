<?php
$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y'
?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Cash Book</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style type="text/css">
    .form-label {
        display: block !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Cash Book Report</h3>
            </div>

            <div class="card-body">
                <?php echo Form::open(['route' => 'reports.cash-book', 'method' => 'post', 'class' => 'form-inline']); ?>

                <div class="row w-100">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label']); ?>

                            <div class="input-group date">
                                <div class="input-group-prepend"><span class="input-group-text"><span class="fa fa-calendar"></span></span></div>
                                <?php echo Form::text('date', $date, ['class' => 'form-control', 'required', 'id' => 'date']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
                        <button type="submit" formaction="<?php echo e(route('reports.cash-book.print')); ?>" class="btn btn-danger" formtarget="_blank"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>                    </div>
                </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</div>

<?php if(isset($bookings)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Cash Book Summary for <?php echo e(date($date_format_setting, strtotime($date))); ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Total Income:</strong> <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_income, 2)); ?>

                    </div>
                    <div class="col-md-4">
                        <strong>Total Expenses:</strong> <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_expenses, 2)); ?>

                    </div>
                    <div class="col-md-4">
                        <strong>Cash Balance:</strong> <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($cash_balance, 2)); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Booking Details</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Vehicle</th>
                            <th>Pickup Time</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($booking->id); ?></td>
                            <td><?php echo e($booking->customer->name ?? 'N/A'); ?></td>
                            <td><?php echo e($booking->vehicle->makeModel ?? 'N/A'); ?></td>
                            <td><?php echo e(date($date_format_setting, strtotime($booking->pickup))); ?></td>
                            <td><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($booking->getMeta('total_price'), 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    $('#myTable').DataTable({
        "language": {
            "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
        },
        "ordering": false
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/cash-book.blade.php ENDPATH**/ ?>
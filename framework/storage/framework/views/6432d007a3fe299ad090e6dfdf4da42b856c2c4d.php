<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
    .card { box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; margin-bottom: 20px; }
    .card-header { background-color: #4a5568; color: white; }
    .btn-generate { background-color: #4299e1; border-color: #4299e1; }
    .btn-generate:hover { background-color: #3182ce; border-color: #3182ce; }
    .btn-print { background-color: #ed8936; border-color: #ed8936; }
    .btn-print:hover { background-color: #dd6b20; border-color: #dd6b20; }
    .summary-section { border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px; }
    .summary-section:last-child { border-bottom: none; }
    .summary-title { color: #2d3748; font-weight: bold; margin-bottom: 10px; }
    .summary-item { display: flex; justify-content: space-between; margin-bottom: 5px; }
    .summary-item strong { color: #4a5568; }
    .cash-balance { font-size: 1.25rem; color: #2b6cb0; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="text-center mb-4" style="color: #2d3748;">Cash Book Report</h1>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title m-0">Generate Report</h3>
        </div>
        <div class="card-body">
            <?php echo Form::open(['route' => 'reports.cash-book', 'method' => 'post']); ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label font-weight-bold']); ?>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <?php echo Form::text('date', $date, ['class' => 'form-control', 'required', 'id' => 'date']); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-generate text-white mr-2">
                        <i class="fa fa-chart-bar mr-1"></i> <?php echo app('translator')->getFromJson('fleet.generate_report'); ?>
                    </button>
                    <button type="submit" formaction="<?php echo e(route('reports.cash-book.print')); ?>" class="btn btn-print text-white" formtarget="_blank">
                        <i class="fa fa-print mr-1"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?>
                    </button>
                </div>
            </div>
            <?php echo Form::close(); ?>

        </div>
    </div>

    <?php if(isset($bookings)): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title m-0">Cash Book Summary for <?php echo e(date(Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y', strtotime($date))); ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 summary-section">
                        <h4 class="summary-title">Income</h4>
                        <div class="summary-item">
                            <strong>Booking Income:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_income - $tyre_sales, 2)); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Tyre Sales:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($tyre_sales, 2)); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Total Income:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_income, 2)); ?></span>
                        </div>
                    </div>
                    <div class="col-md-6 summary-section">
                        <h4 class="summary-title">Expenses</h4>
                        <div class="summary-item">
                            <strong>Fuel Costs:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($fuel_costs, 2)); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Driver Advances:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($other_costs, 2)); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Legal Costs:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($legal_costs, 2)); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Tyre Purchase:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($tyre_purchase, 2)); ?></span>
                        </div>
                        <div class="summary-item">
                            <strong>Total Expenses:</strong>
                            <span><?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($total_expenses, 2)); ?></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <h4 class="cash-balance">Cash Balance: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e(number_format($cash_balance, 2)); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#date').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/cash-book.blade.php ENDPATH**/ ?>
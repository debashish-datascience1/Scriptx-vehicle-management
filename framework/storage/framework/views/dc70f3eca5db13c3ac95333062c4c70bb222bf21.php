<?php ($date_format_setting = Hyvikk::get('date_format') ? Hyvikk::get('date_format') : 'd-m-Y'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="#">Reports</a></li>
    <li class="breadcrumb-item active">Fuel Purchase Report</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra_css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
    <style type="text/css">
        .form-label {
            display: block !important;
        }
        .mybtn1 {
            padding: 4px 8px;
        }
        .checkbox,
        #chk_all {
            width: 20px;
            height: 20px;
        }
        .fullsize {
            width: 100% !important;
        }
        .newrow {
            margin: 0 auto;
            width: 100%;
            margin-bottom: 15px;
        }
        .dateShow {
            padding-right: 13px
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Fuel Purchase Report</h3>
                </div>

                <div class="card-body">
                    <?php echo Form::open(['route' => 'reports.fuel-purchase-post', 'method' => 'post', 'class' => 'form-block']); ?>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo Form::label('vendor_id', __('fleet.vendor'), ['class' => 'form-label']); ?>

                                <?php echo Form::select('vendor_id', 
                                    $vendors, 
                                    $request['vendor_id'] ?? null, [
                                    'class' => 'form-control',
                                    'id' => 'vendor_id',
                                    'placeholder' => 'Select Vendor',
                                ]); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']); ?>

                                <?php echo Form::select('fuel_type', 
                                    $fuel_types, 
                                    $request['fuel_type'] ?? null, [
                                    'class' => 'form-control vehicles fullsize',
                                    'id' => 'fuel_type',
                                    'placeholder' => 'Select Fuel Type',
                                ]); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo Form::label('from_date', __('fleet.fromDate'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('from_date', 
                                    isset($request['from_date']) ? Helper::indianDateFormat($request['from_date']) : null,
                                    ['class' => 'form-control', 'readonly']
                                ); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <?php echo Form::label('to_date', __('fleet.toDate'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('to_date', 
                                    isset($request['to_date']) ? Helper::indianDateFormat($request['to_date']) : null,
                                    ['class' => 'form-control', 'readonly']
                                ); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-info gen_report"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
                            <button type="submit" formaction="<?php echo e(route('print.fuel-purchase.report')); ?>" formtarget="_blank"
                                class="btn btn-danger print_report">
                                <i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?>
                            </button>
                        </div>
                    </div>
                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
    </div>

    <?php if(isset($transactions)): ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Fuel Purchase Report</h3>
                        </div>

                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th><?php echo app('translator')->getFromJson('fleet.vendor'); ?></th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($k + 1); ?></td>
                                            <td><?php echo e(optional($row->vendor)->name ?? '-'); ?></td>
                                            <td><?php echo e($row->quantity); ?></td>
                                            <td><?php echo e(bcdiv($row->amount, 1, 2)); ?></td>
                                            <td><?php echo e($row->remarks ?? '-'); ?></td>
                                            <td><?php echo e(Helper::getCanonicalDate($row->date, 'default')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <br>
                            <table class="table">
                                <tr>
                                    <th style="float:right">Total Amount : <?php echo e(Hyvikk::get('currency')); ?>

                                        <?php echo e(bcdiv($total_amount, 1, 2)); ?></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" />');
            });

            var myTable = $('#myTable').DataTable({
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                    ]
                }],
                "language": {
                    "url": '<?php echo e(__('fleet.datatable_lang')); ?>',
                },
                "initComplete": function() {
                    myTable.columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change', function() {
                            that.search(this.value).draw();
                        });
                    });
                }
            });

            // Dates
            $('#from_date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            $('#to_date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            $(".gen_report, .print_report").on("click", function() {
                var blankTest = /\S/
                var vendor_id = $("#vendor_id").val();
                if (!blankTest.test(vendor_id)) {
                    alert("Please choose a vendor");
                    $("#vendor_id").focus();
                    return false;
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\VehicleMgmt\framework\resources\views/reports/fuel-purchase.blade.php ENDPATH**/ ?>
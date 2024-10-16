<?php $__env->startSection('extra_css'); ?>
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
    <style>
        .remove-section {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .fastag-entry {
            position: relative;
            padding-bottom: 30px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .remove-button-container {
            position: absolute;
            bottom: 0;
            right: 0;
        }
        #grand-total-container {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("breadcrumb"); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route("fastag.index")); ?>">Fastag</a></li>
    <li class="breadcrumb-item active">Edit Fastag Entry</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-warning">
            <div class="card-header with-border">
                <h3 class="card-title">Edit Fastag Entry</h3>
            </div>

            <div class="card-body">
                <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php echo Form::open(['route' => ['fastag.update', $fastag->id], 'method' => 'put', 'id' => 'fastagForm']); ?>

                <?php echo Form::hidden('grand_total', $fastag->total_amount, ['id' => 'grand_total_input']); ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Form::label('fastag', 'Fastag Number', ['class' => 'form-label required']); ?>

                            <select name="fastag" class="form-control fastag-select" required>
                                <option value="">Select Fastag</option>
                                <?php $__currentLoopData = $bank_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($account->id); ?>" <?php echo e($fastag->fastag == "{$account->bank} - {$account->account_no}" ? 'selected' : ''); ?>>
                                        <?php echo e($account->bank); ?> - <?php echo e($account->account_no); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="fastag-entries">
                    <?php $__currentLoopData = $fastagEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="fastag-entry">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('vehicle_id[]',__('fleet.selectVehicle'), ['class' => 'form-label']); ?>

                                    <select name="vehicle_id[]" class="form-control vehicle-select" required>
                                        <option value="">-</option>
                                        <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vehicle->id); ?>" <?php echo e($entry->registration_number == "{$vehicle->make} - {$vehicle->model} - {$vehicle->license_plate}" ? 'selected' : ''); ?>>
                                            <?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('date[]', 'Date', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('date[]', $entry->date, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('toll_gate_name[]', 'Toll Gate Name', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('toll_gate_name[]', $entry->toll_gate_name, ['class' => 'form-control', 'required']); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php echo Form::label('amount[]', 'Amount', ['class' => 'form-label required']); ?>

                                    <?php echo Form::number('amount[]', $entry->amount, ['class' => 'form-control amount-input', 'required', 'step' => '0.01']); ?>

                                </div>
                            </div>
                        </div>
                        <?php if($index > 0): ?>
                        <div class="remove-button-container">
                            <span class="remove-section">Remove</span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div id="grand-total-container">
                    Grand Total: <span id="grand-total"><?php echo e($fastag->total_amount); ?></span>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="button" id="add-more" class="btn btn-info">Add More</button>
                        <?php echo Form::submit('Update', ['class' => 'btn btn-warning']); ?>

                    </div>
                </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
    let entryCount = <?php echo e(count($fastagEntries)); ?>;

    initializeSelect2();
    initializeDatepicker();
    initializeAmountListeners();

    $('#add-more').click(function() {
        entryCount++;
        const newEntry = createNewEntry(entryCount);
        $('#fastag-entries').append(newEntry);
        initializeSelect2($('#fastag-entries .fastag-entry:last'));
        initializeDatepicker($('#fastag-entries .fastag-entry:last'));
        initializeAmountListeners($('#fastag-entries .fastag-entry:last'));
    });

    $(document).on('click', '.remove-section', function() {
        $(this).closest('.fastag-entry').remove();
        updateGrandTotal();
    });

    function createNewEntry(index) {
        return `
            <div class="fastag-entry">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vehicle_id_${index}" class="form-label">Select Vehicle</label>
                            <select name="vehicle_id[]" id="vehicle_id_${index}" class="form-control vehicle-select" required>
                                <option value="">-</option>
                                <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> - <?php echo e($vehicle->license_plate); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_${index}" class="form-label required">Date</label>
                            <input type="text" name="date[]" id="date_${index}" class="form-control datepicker" required autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="toll_gate_name_${index}" class="form-label required">Toll Gate Name</label>
                            <input type="text" name="toll_gate_name[]" id="toll_gate_name_${index}" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount_${index}" class="form-label required">Amount</label>
                            <input type="number" name="amount[]" id="amount_${index}" class="form-control amount-input" required step="0.01">
                        </div>
                    </div>
                </div>
                <div class="remove-button-container">
                    <span class="remove-section">Remove</span>
                </div>
            </div>
        `;
    }

    function initializeSelect2(context = $('body')) {
        context.find('.vehicle-select').select2({placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"});
        $('.fastag-select').select2({placeholder: "Select Fastag"});
    }

    function initializeDatepicker(context = $('body')) {
        context.find('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }

    function initializeAmountListeners(context = $('body')) {
        context.find('.amount-input').on('input', function() {
            updateGrandTotal();
        });
    }

    function updateGrandTotal() {
        let total = 0;
        $('.amount-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#grand-total').text(total.toFixed(2));
        $('#grand_total_input').val(total.toFixed(2));
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/fastag/edit.blade.php ENDPATH**/ ?>
<?php $__env->startSection('extra_css'); ?>
    <style type="text/css">
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            display: none;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        #available_stock_container {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        #available_stock {
            background-color: #fff;
        }
    </style>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('fuel.index')); ?>"><?php echo app('translator')->getFromJson('fleet.fuel'); ?></a></li>
    <li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.edit_fuel'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.edit_fuel'); ?></h3>
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

                    <?php echo Form::open(['route' => ['fuel.update', $data->id], 'method' => 'PATCH']); ?>

                    <?php echo Form::hidden('user_id', Auth::user()->id); ?>

                    <?php echo Form::hidden('vehicle_id', $vehicle_id); ?>

                    <?php echo Form::hidden('id', $data->id); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('vehicle_id', __('fleet.selectVehicle'), ['class' => 'form-label']); ?>

                                <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                                    <option value="">-</option>
                                    <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vehicle->id); ?>" <?php if($vehicle->id == $vehicle_id): ?> selected <?php endif; ?>>
                                            <?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> -
                                            <?php echo e($vehicle->license_plate); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('group_transport_id', __('fleet.selectTransporter'), ['class' => 'form-label']); ?>

                                <select id="group_transport_id" name="group_transport_id" class="form-control">
                                    <option value="">-</option>
                                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($group->id); ?>" <?php echo e($data->group_transport_id == $group->id ? 'selected' : ''); ?>>
                                            <?php echo e($group->group_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('date', __('fleet.date'), ['class' => 'form-label']); ?>

                                <div class='input-group'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                    <?php echo Form::text('date', Helper::indianDateFormat($data->date), ['class' => 'form-control', 'required']); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('start_meter', __('fleet.start_meter'), ['class' => 'form-label']); ?>

                                <?php echo Form::number('start_meter', $data->start_meter, ['class' => 'form-control', 'required']); ?>

                                <small><?php echo app('translator')->getFromJson('fleet.meter_reading'); ?></small>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('reference', __('fleet.reference'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('reference', $data->reference, ['class' => 'form-control']); ?>

                            </div>

                            <div class="form-group">
                                <?php echo Form::label('province', __('fleet.province'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('province', $data->province, ['class' => 'form-control']); ?>

                            </div>

                            <div class="form-group">
                                <?php echo Form::label('note', __('fleet.note'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('note', $data->note, ['class' => 'form-control']); ?>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <h4><?php echo app('translator')->getFromJson('fleet.complete_fill_up'); ?></h4>
                                </div>
                                <div class="col-md-6">
                                    <label class="switch">
                                        <input type="checkbox" name="complete" value="1" <?php if($data->complete == 1): ?> checked <?php endif; ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-solid">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.fuel_coming_from'); ?></h3>
                                </div>
                                <div class="card-body">
                                    <select id="vendor_name" name="vendor_name" class="form-control" required>
                                        <option value="">- Select Vendor -</option>
                                        <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($vendor->id); ?>" <?php if($data->vendor_name == $vendor->id): ?> selected <?php endif; ?>>
                                                <?php echo e($vendor->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <div id="available_stock_container" style="display: none;" class="mt-3">
                                        <label class="form-label">Available Stock</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="available_stock" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><?php echo e(Hyvikk::get('fuel_unit')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-solid">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php echo app('translator')->getFromJson('fleet.fuel'); ?>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <?php echo Form::label('fuel_type', __('fleet.fuelType'), ['class' => 'form-label']); ?>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-exclamation"></i></span>
                                            </div>
                                            <?php echo Form::select('fuel_type', $oil, $data->fuel_type, [
                                                'class' => 'form-control',
                                                'required',
                                            ]); ?>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('qty', __('fleet.qty') . ' (' . Hyvikk::get('fuel_unit') . ')', ['class' => 'form-label']); ?>

                                        <?php echo Form::text('qty', $data->qty, ['class' => 'form-control', 'required']); ?>

                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('cost_per_unit', __('fleet.cost_per_unit'), ['class' => 'form-label']); ?>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span>
                                            </div>
                                            <?php echo Form::text('cost_per_unit', $data->cost_per_unit, ['class' => 'form-control', 'required']); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-solid">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        GST
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php echo Form::label('Is GST?', __('fleet.isGst'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::select('is_gst', $is_gst, $data->is_gst, [
                                                        'class' => 'form-control',
                                                        'id' => 'is_gst',
                                                        'required',
                                                    ]); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo Form::label('cgst', __('fleet.cgst') . ' %', ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('cgst', $data->cgst, [
                                                        'class' => 'form-control',
                                                        'id' => 'cgst',
                                                        'placeholder' => 'Enter %',
                                                        'onkeypress' => 'return isNumber(event,this)',
                                                        'required',
                                                    ]); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php echo Form::label('cgst_amt', __('fleet.cgst_amt'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('cgst_amt', $data->cgst_amt, ['class' => 'form-control', 'id' => 'cgst_amt', 'readonly']); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo Form::label('sgst', __('fleet.sgst') . ' %', ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('sgst', $data->sgst, [
                                                        'class' => 'form-control',
                                                        'id' => 'sgst',
                                                        'placeholder' => 'Enter %',
                                                        'onkeypress' => 'return isNumber(event,this)',
                                                        'required',
                                                    ]); ?>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php echo Form::label('sgst_amt', __('fleet.sgst_amt'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('sgst_amt', $data->sgst_amt, ['class' => 'form-control', 'id' => 'sgst_amt', 'readonly']); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo Form::label('total_amount', __('fleet.total_amount'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('total_amount', $data->total_amount, ['class' => 'form-control', 'id' => 'total_amount', 'readonly']); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo Form::submit(__('fleet.update'), ['class' => 'btn btn-success']); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    var getAvailableStockUrl = "<?php echo e(route('get_available_stock')); ?>";
    var fuelGstCalculateUrl = "<?php echo e(route('fuel.fuel_gstcalculate')); ?>";
    var csrfToken = "<?php echo e(csrf_token()); ?>";
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
    <!-- bootstrap datepicker -->
    <script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
    <script type="text/javascript">
        // Check Number and Decimal
        function isNumber(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
            if (
                (charCode != 46 || $(element).val().indexOf('.') != -1) && // "." CHECK DOT, AND ONLY ONE.
                (charCode < 48 || charCode > 57))
                return false;
            return true;
        }

        $(document).ready(function() {
            // Keep track of original stock for calculations
            let originalStock = 0;

            // Initialize Select2 for dropdowns
            $("#vehicle_id").select2({
                placeholder: "<?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?>"
            });

            $("#vendor_name").select2({
                placeholder: "<?php echo app('translator')->getFromJson('fleet.select_fuel_vendor'); ?>"
            });

            $("#group_transport_id").select2({
                placeholder: "<?php echo app('translator')->getFromJson('fleet.selectGroup'); ?>"
            });

            // Initialize datepicker
            $('#date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            // Handle datepicker change
            $("#date").on("change", function(e) {
                var date = e.target.value;
            });

            // Initialize iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            // Handle vendor selection change
            $("#vendor_name").on('change', function() {
                var selectedVendorName = $(this).find("option:selected").text().trim();

                if (selectedVendorName === 'Ranisati Own Stock') {
                    $("#available_stock_container").show();
                    fetchAvailableStock();
                } else {
                    $("#available_stock_container").hide();
                    $("#available_stock").val('');
                    originalStock = 0;
                }
            });

            function fetchAvailableStock() {
                $.ajax({
                    url: getAvailableStockUrl,
                    type: 'GET',
                    success: function(response) {
                        $("#available_stock").val(response.available_stock);
                        originalStock = parseFloat(response.available_stock);
                        updateAvailableStockDisplay();
                    },
                    error: function(xhr) {
                        console.error('Error fetching stock:', xhr);
                        $("#available_stock").val('Error loading stock');
                        originalStock = 0;
                    }
                });
            }

            function updateAvailableStockDisplay() {
                var selectedVendorName = $("#vendor_name").find("option:selected").text().trim();

                if (selectedVendorName === 'Ranisati Own Stock') {
                    var requestedQty = parseFloat($("#qty").val()) || 0;
                    var updatedStock = originalStock - requestedQty;

                    $("#available_stock").val(updatedStock.toFixed(2));

                    if (updatedStock < 0) {
                        $("#available_stock").css('color', 'red');
                    } else {
                        $("#available_stock").css('color', 'black');
                    }
                }
            }

            // Handle quantity changes
            $("#qty").on('input change', function() {
                var selectedVendorName = $("#vendor_name").find("option:selected").text().trim();

                if (selectedVendorName === 'Ranisati Own Stock') {
                    var requestedQty = parseFloat($(this).val()) || 0;

                    if (requestedQty > originalStock) {
                        alert("Quantity cannot exceed available stock of " + originalStock);
                        $(this).val(originalStock);
                        requestedQty = originalStock;
                    }

                    updateAvailableStockDisplay();
                }
            });

            // Handle form submission validation
            $("form").submit(function(e) {
                var qty = $("#qty").val();
                var costa = $("#cost_per_unit").val();
                var date = $("#date").val();
                // Remove this line: var group = $("#group_transport").val();
                var selectedVendorName = $("#vendor_name").find("option:selected").text().trim();

                // Remove this entire block:
                /*
                if (!group) {
                    alert("Please select a transport group");
                    $("#group_transport").focus();
                    e.preventDefault();
                    return false;
                }
                */

                // Keep the rest of your validation code
                if (selectedVendorName === 'Ranisati Own Stock') {
                    var updatedStock = parseFloat($("#available_stock").val());

                    if (updatedStock < 0) {
                        alert("Cannot proceed with negative stock value");
                        e.preventDefault();
                        return false;
                    }
                }

                if (!date) {
                    alert("Date cannot be empty");
                    $("#date").focus();
                    e.preventDefault();
                    return false;
                }

                if (!qty || qty == 0 || !costa || costa == 0) {
                    if (!qty || qty == 0) {
                        alert("Quantity cannot be empty or zero");
                        $("#qty").focus();
                        e.preventDefault();
                        return false;
                    }
                    if (!costa || costa == 0) {
                        alert("Cost per Unit cannot be empty or zero");
                        $("#cost_per_unit").focus();
                        e.preventDefault();
                        return false;
                    }
                    e.preventDefault();
                    return false;
                }
            });

            // Handle cost calculations
            $(document).on('keyup', '#cost_per_unit,#qty,#cgst,#sgst', function() {
                var cost = $("#cost_per_unit").val();
                var qty = $("#qty").val();
                var cgst = $("#cgst").val();
                var sgst = $("#sgst").val();

                var sendData = {
                    _token: csrfToken,
                    cost: cost,
                    qty: qty,
                    cgst: cgst,
                    sgst: sgst
                };

                $.post(fuelGstCalculateUrl, sendData)
                    .done(function(data) {
                        if (!isNaN(data.total) && data.total != 0) {
                            $(".smallfuel").show();
                            $(".fueltot").html(data.total);
                        } else {
                            $(".smallfuel").hide();
                            $(".fueltot").html('');
                        }

                        if (!isNaN(data.cgstval) && data.cgstval != 0) {
                            $("#cgst_amt").val(data.cgstval);
                        } else {
                            $("#cgst_amt").val('');
                        }

                        if (!isNaN(data.sgstval) && data.sgstval != 0) {
                            $("#sgst_amt").val(data.sgstval);
                        } else {
                            $("#sgst_amt").val('');
                        }

                        if (!isNaN(data.grandtotal) && data.grandtotal != 0) {
                            $("#total_amount").val(data.grandtotal);
                        } else {
                            $("#total_amount").val('');
                        }
                    });
            });

            // Handle fuel type change
            $("#fuel_type").change(function() {
                var meter = $("#start_meter");
                $(this).val() == '3' ? meter.prop('required', false) : meter.prop('required', true);
            });

            // Handle GST selection change
            $("#is_gst").change(function() {
                var is_gst = $("#is_gst").val();
                var cgst = $("#cgst");
                var sgst = $("#sgst");

                if (is_gst == 1) {
                    cgst.prop('readonly', false).prop('required', true);
                    sgst.prop('readonly', false).prop('required', true);
                } else {
                    cgst.prop('readonly', true).prop('required', false).val('');
                    sgst.prop('readonly', true).prop('required', false).val('');
                    $("#sgst_amt").val('');
                    $("#cgst_amt").val('');
                    $("#total_amount").val('');
                }
            });

            // Trigger initial calculations on page load if values exist
            if ($("#cost_per_unit").val() && $("#qty").val()) {
                $("#cost_per_unit").trigger('keyup');
            }

            // Check vendor name on page load
            if ($("#vendor_name").find("option:selected").text().trim() === 'Ranisati Own Stock') {
                $("#available_stock_container").show();
                fetchAvailableStock();
            }
            $("#is_gst").trigger('change');

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\VehicleMgmt\framework\resources\views/fuel/edit.blade.php ENDPATH**/ ?>
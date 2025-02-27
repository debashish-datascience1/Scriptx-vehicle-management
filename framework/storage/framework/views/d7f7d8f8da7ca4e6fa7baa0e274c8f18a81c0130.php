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
    <li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.add_fuel'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.addFuel'); ?></h3>
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

                    <?php echo Form::open(['route' => 'fuel.store', 'method' => 'post']); ?>

                    <?php echo Form::hidden('user_id', Auth::user()->id); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo Form::label('vehicle_id', __('fleet.selectVehicle'), ['class' => 'form-label']); ?>

                                <select id="vehicle_id" name="vehicle_id" class="form-control" required>
                                    <option value="">-</option>
                                    <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->make); ?> - <?php echo e($vehicle->model); ?> -
                                            <?php echo e($vehicle->license_plate); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('group_transport', __('fleet.selectTransporter'), ['class' => 'form-label']); ?>

                                <select id="group_transport" name="group_transport" class="form-control">
                                    <option value="">-</option>
                                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->group_name); ?></option>
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
                                    <?php echo Form::text('date', Helper::indianDateFormat(), ['class' => 'form-control', 'required', 'readonly']); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('start_meter', __('fleet.start_meter'), ['class' => 'form-label']); ?>

                                <?php echo Form::number('start_meter', null, ['class' => 'form-control', 'required']); ?>

                                <small><?php echo app('translator')->getFromJson('fleet.meter_reading'); ?></small>
                            </div>

                            <div class="form-group">
                                <?php echo Form::label('reference', __('fleet.reference'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('reference', null, ['class' => 'form-control']); ?>

                            </div>

                            <div class="form-group">
                                <?php echo Form::label('province', __('fleet.province'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('province', null, ['class' => 'form-control']); ?>

                            </div>

                            <div class="form-group">
                                <?php echo Form::label('note', __('fleet.note'), ['class' => 'form-label']); ?>

                                <?php echo Form::text('note', null, ['class' => 'form-control']); ?>

                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <h4><?php echo app('translator')->getFromJson('fleet.complete_fill_up'); ?></h4>
                                </div>
                                <div class="col-md-6">
                                    <label class="switch">
                                        <input type="checkbox" name="complete" value="1">
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
                                            <option value="<?php echo e($vendor->id); ?>"> <?php echo e($vendor->name); ?> </option>
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
                                            <?php echo Form::select('fuel_type', $oil, null, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Choose fuel type',
                                                'required',
                                            ]); ?>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('qty', __('fleet.qty') . ' (' . Hyvikk::get('fuel_unit') . ')', ['class' => 'form-label']); ?>

                                        <?php echo Form::text('qty', '0.00', ['class' => 'form-control', 'required']); ?>

                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('cost_per_unit', __('fleet.cost_per_unit'), ['class' => 'form-label']); ?>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span>
                                            </div>
                                            <?php echo Form::text('cost_per_unit', '0.00', ['class' => 'form-control', 'required']); ?>

                                        </div>
                                    </div>
                                    <small class="smallfuel" style="display: none;">Total Fuel Price &nbsp;<span
                                            class="fa fa-inr"></span>&nbsp;<span class="fueltot"></span></small>
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

                                                    <?php echo Form::select('is_gst', $is_gst, null, [
                                                        'class' => 'form-control',
                                                        'id' => 'is_gst',
                                                        'placeholder' => 'Select',
                                                        'required',
                                                    ]); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo Form::label('cgst', __('fleet.cgst') . ' %', ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('cgst', null, [
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

                                                    <?php echo Form::text('cgst_amt', null, ['class' => 'form-control', 'id' => 'cgst_amt', 'readonly']); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo Form::label('sgst', __('fleet.sgst') . ' %', ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('sgst', null, [
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

                                                    <?php echo Form::text('sgst_amt', null, ['class' => 'form-control', 'id' => 'sgst_amt', 'readonly']); ?>

                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo Form::label('total_amount', __('fleet.total_amount'), ['class' => 'form-label']); ?>

                                                    <?php echo Form::text('total_amount', null, ['class' => 'form-control', 'id' => 'total_amount', 'readonly']); ?>

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
                            <?php echo Form::submit(__('fleet.add_fuel'), ['class' => 'btn btn-success', 'id' => 'addBtn']); ?>

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

            $("#group_transport").select2({
                placeholder: "<?php echo app('translator')->getFromJson('fleet.selectGroup'); ?>"
            });

            // Initialize datepicker
            $('#date').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            $("#date").on("dp.change", function(e) {
                var date = e.date.format("dd-mm-yyyy");
            });

            // Initialize iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            // Handle fuel from radio change
            $(".fuel_from").change(function() {
                if ($("#r1").attr("checked")) {
                    $('#vendor_name').show();
                } else {
                    $('#vendor_name').hide();
                }
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

            // Handle Add Button click
            $(document).on("click", "#addBtn", function() {
                var selectedVendorName = $("#vendor_name").find("option:selected").text().trim();
                var qty = $("#qty").val();
                var costa = $("#cost_per_unit").val();
                var date = $("#date").val();
                var group = $("#group_transport").val();

                // Validate group selection
                if (!group) {
                    alert("Please select a transport group");
                    $("#group_transport").focus();
                    return false;
                }

                if (selectedVendorName === 'Ranisati Own Stock') {
                    var updatedStock = parseFloat($("#available_stock").val());

                    if (updatedStock < 0) {
                        alert("Cannot proceed with negative stock value");
                        return false;
                    }
                }

                if (date == '' || date == null) {
                    alert("Date cannot be empty");
                    $("#date").focus();
                    return false;
                }

                if (qty == null || qty == 0 || costa == null || costa == 0) {
                    alert("Quantity and Per Unit cannot be empty or zero");
                    if (qty == null || qty == 0) {
                        $("#qty").focus();
                        return false;
                    }
                    if (costa == null || costa == 0) {
                        $("#cost_per_unit").focus();
                        return false;
                    }
                    return false;
                } else if ((qty == null || qty == 0) && (costa != null || costa != 0)) {
                    alert("Quantity cannot be empty or zero");
                    if (qty == null || qty == 0) {
                        $("#qty").focus();
                        return false;
                    }
                    return false;
                } else if ((qty != null || qty != 0) && (costa == null || costa == 0)) {
                    alert("Cost per Unit cannot be empty or zero");
                    if (costa == null || costa == 0) {
                        $("#cost_per_unit").focus();
                        return false;
                    }
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
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\VehicleMgmt\framework\resources\views/fuel/create.blade.php ENDPATH**/ ?>
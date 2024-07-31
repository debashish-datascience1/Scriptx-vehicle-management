<?php $__env->startSection('extra_css'); ?>
<?php
$date_format_setting = (Hyvikk::get('date_format')) ? Hyvikk::get('date_format') : 'd-m-Y';
?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style>
    .fullsize{width: 100% !important;}
    .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
    .dateShow{padding-right: 13px;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.stock_report'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.stock_report'); ?></h3>
            </div>

            <div class="card-body">
                <?php echo Form::open(['route' => 'reports.stock','method'=>'post','class'=>'form-block']); ?>

                <div class="row newrow">
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('parts_id',__('fleet.selectPart'), ['class' => 'form-label']); ?>

                            <?php echo Form::select('parts_id[]',$options,'all',['class'=>'form-control parts_id','id'=>'parts_id']); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('category_id', __('fleet.selectCategory'), ['class' => 'form-label']); ?>

                            <?php echo Form::select('category_id[]', $categories, 'all', ['class' => 'form-control category_id', 'id' => 'category_id','multiple' => 'multiple']); ?>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php echo Form::label('date1','From',['class' => 'form-label dateShow']); ?>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                <?php echo Form::text('date1', isset($request['date1']) ? Helper::indianDateFormat($request['date1']) : null,['class' => 'form-control','placeholder'=>'From Date','readonly']); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?php echo Form::label('date2','To',['class' => 'form-label dateShow']); ?>

                            <div class="input-group">
                              <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                              <?php echo Form::text('date2', isset($request['date2']) ? Helper::indianDateFormat($request['date2']) : null,['class' => 'form-control','placeholder'=>'To Date','readonly']); ?>

                            </div>
                        </div>
                    </div>
                </div>    
                <div class="row newrow">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
                        <button type="submit" formaction="<?php echo e(url('admin/print-stock-report')); ?>" formtarget="_blank" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
                    </div>
                </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</div>

<?php if(isset($parts)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    Parts Stock Report
                </h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Part Name</th>
                            <th>Category</th>
                            <th>Manufacturer</th>
                            <th>Stock</th>
                            <th>Tyres Used</th>
                            <th>Tyres InStock</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $total_stock = 0;
                        $total_tyres_used = 0;
                    ?>
                    <?php $__currentLoopData = $parts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$part): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $total_stock += $part->stock ?? 0;
                            $total_tyres_used += $tyres_used[$part->id]->total_used ?? 0;
                        ?>
                        <tr>
                            <td><?php echo e($k+1); ?></td>
                            <td><?php echo e($part->item ?? 'N/A'); ?></td>
                            <td><?php echo e($part->category->name ?? 'N/A'); ?></td>
                            <td><?php echo e($part->manufacturer_details->name ?? 'N/A'); ?></td>
                            <td><?php echo e($part->stock ?? 'N/A'); ?></td>
                            <td><?php echo e($tyres_used[$part->id]->total_used ?? 0); ?></td>
							<td>
								<?php
									$tyre_numbers = $part->tyres_used ?? '';
									if (!empty($tyre_numbers)) {
										$numbers_array = explode(',', $tyre_numbers);
										$display_numbers = array_slice($numbers_array, 0, 2);
										$output = implode(', ', $display_numbers);
										if (count($numbers_array) > 2) {
											$output .= ', ...';
										}
									} else {
										$output = 'N/A';
										$numbers_array = [];
									}
								?>
								<?php echo e($output); ?>

								<?php if(!empty($tyre_numbers) && count($numbers_array) > 2): ?>
									<button class="btn btn-sm btn-info show-tyres" data-part-id="<?php echo e($part->id); ?>" data-part-name="<?php echo e($part->item); ?>">Show</button>
								<?php endif; ?>
							</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>SL#</th>
                            <th>Part Name</th>
                            <th>Category</th>
                            <th>Manufacturer</th>
                            <th>Stock</th>
                            <th>Tyres Used</th>
                            <th>Tyre Numbers</th>
                        </tr>
                    </tfoot>
                </table>
                <br>
                <table class="table">
                    <tr>
                        <th style="float:right">Total Tyres Used: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($total_tyres_used); ?></th>
                        <th style="float:right">Total Stock: <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($total_stock); ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!-- Modal -->
<div class="modal fade" id="tyreModal" tabindex="-1" role="dialog" aria-labelledby="tyreModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tyreModalLabel">Tyre Numbers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="tyreModalBody">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#date1,#date2').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    
    $('#parts_id').select2({
        placeholder: 'Select Parts'
    });

    // $('#category_id').select2({
    //     placeholder: 'Select Category'
    // });
    $('#category_id').select2({
    placeholder: 'Select Category',
    multiple: true,
    allowClear: true
    });

    // Set 'All' as default selection
    $('#parts_id').val(['all']).trigger('change');
    $('#category_id').val(['all']).trigger('change');


    // Setup - add a text input to each footer cell
    $('#myTable tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });

    // DataTable
    var table = $('#myTable').DataTable({
        "language": {
            "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
        },
        initComplete: function () {
            // Apply the search
            this.api().columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        }
    });
	$(document).on('click', '.show-tyres', function() {
        var partId = $(this).data('part-id');
        var partName = $(this).data('part-name');

        // AJAX call to get tyre numbers
        $.ajax({
            url: '<?php echo e(route("get.tyre.numbers")); ?>',
            method: 'GET',
            data: { part_id: partId },
            success: function(response) {
                var modalContent = '<h6>' + partName + '</h6><hr>';
                if (response && response.length > 0) {
                    modalContent += '<p>' + response.join(', ') + '</p>';
                } else {
                    modalContent += '<p>No tyre numbers available</p>';
                }
                $('#tyreModalBody').html(modalContent);
                $('#tyreModal').modal('show');
            },
            error: function() {
                alert('Error fetching tyre numbers');
            }
        });
    });
});
  
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/stock.blade.php ENDPATH**/ ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.fare_settings'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
.custom .nav-link.active {
    background-color: #21bc6c !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title"><?php echo app('translator')->getFromJson('menu.fare_settings'); ?>
				</h3>
			</div>
			<div class="card-body">
				<div>
					<ul class="nav nav-pills custom">
					<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li class="nav-item"><a href="#<?php echo e(strtolower(str_replace(' ','',$type))); ?>" data-toggle="tab" class="nav-link text-uppercase <?php if(reset($types) == $type): ?> active <?php endif; ?> "> <?php echo e($type); ?> <i class="fa"></i></a></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="tab-content card-body">
							<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php ($type =strtolower(str_replace(" ","",$type))); ?>

							<div class="tab-pane <?php if(strtolower(str_replace(' ','',reset($types))) == $type): ?> active <?php endif; ?>" id="<?php echo e($type); ?>">
								<?php echo Form::open(['url' => 'admin/fare-settings?tab='.$type,'files'=>true,'method'=>'post']); ?>

								<div class="row">
									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_base_fare',__('fleet.general_base_fare'),['class'=>"form-label"]); ?>


										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
											<?php echo Form::number('name['.$type.'_base_fare]',Hyvikk::fare($type.'_base_fare'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_base_km',__('fleet.general_base_km'). " ".Hyvikk::get('dis_format'),['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
											<?php echo Form::number('name['.$type.'_base_km]',Hyvikk::fare($type.'_base_km'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_base_time',__('fleet.general_wait_time'),['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>

											<?php echo Form::number('name['.$type.'_base_time]',Hyvikk::fare($type.'_base_time'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_std_fare',__('fleet.std_fare')." ".Hyvikk::get('dis_format') ,['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
											<?php echo Form::number('name['.$type.'_std_fare]',Hyvikk::fare($type.'_std_fare'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_weekend_base_fare',__('fleet.weekend_base_fare'),['class'=>"form-label"]); ?>


										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
											<?php echo Form::number('name['.$type.'_weekend_base_fare]',Hyvikk::fare($type.'_weekend_base_fare'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_weekend_base_km',__('fleet.weekend_base_km')." ".Hyvikk::get('dis_format'),['class'=>"form-label"]); ?>


										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
											<?php echo Form::number('name['.$type.'_weekend_base_km]',Hyvikk::fare($type.'_weekend_base_km'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_weekend_wait_time',__('fleet.weekend_wait_time'),['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>

											<?php echo Form::number('name['.$type.'_weekend_wait_time]',Hyvikk::fare($type.'_weekend_wait_time'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_weekend_std_fare',__('fleet.weekend_std_fare')." ".Hyvikk::get('dis_format'),['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
											<?php echo Form::number('name['.$type.'_weekend_std_fare]',Hyvikk::fare($type.'_weekend_std_fare'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_night_base_fare',__('fleet.night_base_fare'),['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
											<?php echo Form::number('name['.$type.'_night_base_fare]',Hyvikk::fare($type.'_night_base_fare'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_night_base_km',__('fleet.night_base_km')." ".Hyvikk::get('dis_format'),['class'=>"form-label"]); ?>


										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('dis_format')); ?></span></div>
											<?php echo Form::number('name['.$type.'_night_base_km]',Hyvikk::fare($type.'_night_base_km'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_night_wait_time',__('fleet.night_wait_time'),['class'=>"form-label"]); ?>

										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>

											<?php echo Form::number('name['.$type.'_night_wait_time]',Hyvikk::fare($type.'_night_wait_time'),['class'=>"form-control",'required']); ?>

										</div>
									</div>

									<div class="form-group col-md-3">
										<?php echo Form::label($type.'_night_std_fare',__('fleet.night_std_fare')." ".Hyvikk::get('dis_format'),['class'=>"form-label"]); ?>


										<div class="input-group mb-3">
											<div class="input-group-prepend">
											<span class="input-group-text"><?php echo e(Hyvikk::get('currency')); ?></span></div>
											<?php echo Form::number('name['.$type.'_night_std_fare]',Hyvikk::fare($type.'_night_std_fare'),['class'=>"form-control",'required']); ?>

										</div>
									</div>
								</div>
								<div class="card-footer">
									<div class="col-md-2">
										<div class="form-group">
											<input type="submit"  class="form-control btn btn-success" value="<?php echo app('translator')->getFromJson('fleet.save'); ?>" />
										</div>
									</div>
								</div>
								<?php echo Form::close(); ?>

							</div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
$(document).ready(function() {
  <?php if(isset($_GET['tab']) && $_GET['tab']!=""): ?>
  	$('.nav-pills a[href="#<?php echo e($_GET['tab']); ?>"]').tab('show')
  <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/utilities/fare_settings.blade.php ENDPATH**/ ?>
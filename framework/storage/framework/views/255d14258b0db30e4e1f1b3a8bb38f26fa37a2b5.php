<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.change_details'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row col-md-12">
	<?php if(count($errors) > 0): ?>
      <div class="alert alert-danger">
        <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.change_details'); ?> : <strong><?php echo e($user_data->name); ?></strong></h3>
			</div>
			<div class="card-body">
				<?php echo Form::open(array("url"=>"admin/change-details/".$user_data->id,'files'=>true,'method'=>'POST')); ?>

				<input type="hidden" name="id" value="<?php echo e($user_data->id); ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<?php echo Form::label('name',__('fleet.name'),['class'=>"form-label"]); ?>

							<?php echo Form::text('name',$user_data->name,['class'=>"form-control",'required']); ?>

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php echo Form::label('email',__('fleet.email'),['class'=>"form-label"]); ?>

							<div class="input-group mb-3">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-envelope"></i></span></div>
								<?php echo Form::email('email',$user_data->email,['class'=>"form-control",'required']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
						<?php echo Form::label('image', __('fleet.picture'), ['class' => 'form-label']); ?>

						<?php if($user_data->user_type == "D" && $user_data->getMeta('driver_image') != null): ?>
						<?php if(starts_with($user_data->getMeta('driver_image'),'http')): ?>
						<?php ($src = $user_data->getMeta('driver_image')); ?>
						<?php else: ?>
						<?php ($src=asset('uploads/'.$user_data->getMeta('driver_image'))); ?>
						<?php endif; ?>
						<a href="<?php echo e($src); ?>" target="_blank">View</a>

						<?php elseif($user_data->user_type != "D" && $user_data->getMeta('profile_image') != null): ?>
						<a href="<?php echo e(asset('uploads/'.$user_data->getMeta('profile_image'))); ?>" target="_blank">View
						</a>
						<?php elseif($user_data->user_type == "C" && $user_data->getMeta('profile_pic') != null): ?>
						<?php if(starts_with($user_data->getMeta('profile_pic'),'http')): ?>
						<?php ($src = $user_data->getMeta('profile_pic')); ?>
						<?php else: ?>
						<?php ($src=asset('uploads/'.$user_data->getMeta('profile_pic'))); ?>
						<?php endif; ?>
						<a href="<?php echo e($src); ?>" target="_blank">View
						</a>
						<?php endif; ?>
						<br>
						<?php echo Form::file('image',null,['class' => 'form-control']); ?>

						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<?php echo Form::label('language',__('fleet.language'),['class'=>"form-label"]); ?>

							<select id='language' name='language' class="form-control" required>
								<option value="">-</option>
								<?php if(Auth::user()->getMeta('language')!= null): ?>
								<?php ($language = Auth::user()->getMeta('language')); ?>
								<?php else: ?>
								<?php ($language = Hyvikk::get("language")); ?>
								<?php endif; ?>
								<?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php ($l = explode('-',$lang)); ?>
								<?php if($language == $lang): ?>
								<option value="<?php echo e($lang); ?>" selected> <?php echo e($l[0]); ?></option>
								<?php else: ?>
								<option value="<?php echo e($lang); ?>" > <?php echo e($l[0]); ?> </option>
								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<input type="submit"  class="form-control btn btn-warning"  value="<?php echo app('translator')->getFromJson('fleet.change_details'); ?>" />
					</div>
				</div>
				<?php echo Form::close(); ?>


			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.change_password'); ?> : <strong><?php echo e(Auth::user()->name); ?></strong> </h3>
			</div>
			<div class="card-body">

				<?php echo Form::open(array("url"=>"admin/changepassword/".Auth::user()->id)); ?>

				<input type="hidden" name="id" value="<?php echo e(Auth::user()->id); ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<?php echo Form::label('password',__('fleet.password'),['class'=>"form-label"]); ?>

							<div class="input-group mb-3">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-lock"></i></span></div>
								<input type="password" name="password" class="form-control" required>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<input type="submit"  class="form-control btn btn-warning"  value="<?php echo app('translator')->getFromJson('fleet.change_password'); ?>" />
						</div>
						<?php echo Form::close(); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/utilities/changepass.blade.php ENDPATH**/ ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><?php echo app('translator')->getFromJson('menu.settings'); ?></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.general_settings'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.general_settings'); ?>
        </h3>
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

        <?php echo Form::open(['route' => 'settings.store','files'=>true,'method'=>'post']); ?>

        <div class="row">
          <div class="form-group col-md-4">
            <?php echo Form::label('app_name',__('fleet.app_name'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[app_name]',
            Hyvikk::get('app_name'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-4">
            <?php echo Form::label('email',__('fleet.email'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[email]',
            Hyvikk::get('email'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-4">
            <?php echo Form::label('badd1',__('fleet.badd1'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[badd1]',
            Hyvikk::get('badd1'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-4">
            <?php echo Form::label('badd2',__('fleet.badd2'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[badd2]',
            Hyvikk::get('badd2'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-4">
            <?php echo Form::label('city',__('fleet.city'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[city]',
            Hyvikk::get('city'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-4">
            <?php echo Form::label('state',__('fleet.state'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[state]',
            Hyvikk::get('state'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-3">
            <?php echo Form::label('country',__('fleet.country'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[country]',
            Hyvikk::get('country'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-3">
            <?php echo Form::label('dis_format',__('fleet.dis_format'),['class'=>"form-label"]); ?>

            <?php echo Form::select('name[dis_format]', ['km' => 'km', 'miles' => 'miles'], Hyvikk::get("dis_format"),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-3">
            <?php echo Form::label('fuel_unit',__('fleet.fuel_unit'),['class'=>"form-label"]); ?>

            <?php echo Form::select('name[fuel_unit]', ['gallon' => 'gallon', 'liter' => 'liter'], Hyvikk::get("fuel_unit"),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-3">
            <?php echo Form::label('language',__('fleet.language'),['class'=>"form-label"]); ?>

            <select id='name[language]' name='name[language]' class="form-control" required>
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

          <div class="form-group col-md-2">
            <?php echo Form::label('number_days',__('fleet.noOfDays'),['class'=>"form-label"]); ?>


              <div class="input-group mb-3">
                <?php echo Form::number('name[number_days]',Hyvikk::get('number_days'),['class'=>"form-control",'required','min'=>1]); ?>

                <div class="input-group-append">
                  <span class="input-group-text">day(s)</span>
                </div>
              </div>
          </div>
          <div class="form-group col-md-2">
            <?php echo Form::label('km_travel',__('fleet.kmt'),['class'=>"form-label"]); ?>


              <div class="input-group mb-3">
                <?php echo Form::number('name[km_travel]',Hyvikk::get('km_travel'),['class'=>"form-control",'required','min'=>1]); ?>

                <div class="input-group-append">
                  <span class="input-group-text">km(s)</span>
                </div>
              </div>
          </div>

          <div class="form-group col-md-4">
            <label for="icon_img"> <?php echo app('translator')->getFromJson('fleet.icon_img'); ?></label>
            <?php if(Hyvikk::get('icon_img')!= null): ?>
            <button type="button" class="btn btn-success view1 btn-xs" data-toggle="modal" data-target="#myModal3" id="view" title="<?php echo app('translator')->getFromJson('fleet.image'); ?>" style="margin-bottom: 5px">
            <?php echo app('translator')->getFromJson('fleet.view'); ?>
            </button>
            <?php endif; ?>
            <div class="input-group input-group-sm">
            <?php echo Form::file('icon_img'); ?>

            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="logo_img"> <?php echo app('translator')->getFromJson('fleet.logo_img'); ?></label>
            <?php if(Hyvikk::get('logo_img')!= null): ?>
            <button type="button" class="btn btn-success view2 btn-xs" data-toggle="modal" data-target="#myModal3" id="view" title="<?php echo app('translator')->getFromJson('fleet.image'); ?>" style="margin-bottom: 5px">
            <?php echo app('translator')->getFromJson('fleet.view'); ?>
            </button>
            <?php endif; ?>
            <div class="input-group input-group-sm">
              <?php echo Form::file('logo_img'); ?>

            </div>
          </div>

          <div class="form-group col-md-3">
            <?php echo Form::label('currency',__('fleet.currency'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[currency]',
            Hyvikk::get('currency'),['class'=>"form-control",'required']); ?>

          </div>
          <div class="form-group col-md-3">
            <?php echo Form::label('date_format',__('fleet.date_format'),['class'=>"form-label"]); ?>

            <?php echo Form::select('name[date_format]', ['d-m-Y' => 'dd-mm-yyyy ('.date('d-m-Y').')', 'Y-m-d' => 'yyyy-mm-dd ('.date('Y-m-d').')','m-d-Y'=>'mm-dd-yyyy ('.date('m-d-Y').')'], Hyvikk::get("date_format"),['class'=>"form-control",'required']); ?>

          </div>
          <div class="form-group col-md-3">
            <?php echo Form::label('tax_no',__('fleet.tax_no'),['class'=>"form-label"]); ?>

            <?php echo Form::text('name[tax_no]',
            Hyvikk::get('tax_no'),['class'=>"form-control",'required']); ?>

          </div>

          <div class="form-group col-md-3">
            <?php echo Form::label('tax_charge',__('fleet.tax_charge')." (%)",['class'=>"form-label"]); ?>

            <div class="row">
              <div class="col-md-8">
                <?php echo Form::text('udf1', null,['class' => 'form-control','id'=>'udf1','placeholder'=>'Enter Tax Name']); ?>

              </div>
              <div class="col-md-4">
                <button type="button" class="btn btn-info add_udf"> <?php echo app('translator')->getFromJson('fleet.addNew'); ?></button>
              </div>
            </div>
          </div>
          <?php ($udfs = json_decode(Hyvikk::get('tax_charge'))); ?>

          <?php if($udfs != null): ?>
          <div class="col-md-4"><hr></div>
          <div class="col-md-4"><h4 class="text-center"><?php echo app('translator')->getFromJson('fleet.tax_charge'); ?></h4></div>
          <div class="col-md-4"><hr></div>
          <?php $__currentLoopData = $udfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="row col-md-6">
          <div class="col-md-8">  <div class="form-group"> <label class="form-label text-uppercase"><?php echo e($key); ?></label> <div class="input-group mb-3"><input type="number" name="udf[<?php echo e($key); ?>]" class="form-control" required value="<?php echo e($value); ?>" min=0 step="0.01"> <div class="input-group-append"> <span class="input-group-text fa fa-percent"></span> </div> </div></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
          <div class="blank col-md-12"></div>
          <div class="form-group col-md-12">
            <?php echo Form::label('invoice_text',__('fleet.invoice_text'),['class'=>"form-label"]); ?>

            <?php echo Form::textarea('name[invoice_text]',
            Hyvikk::get('invoice_text'),['class'=>"form-control",'size'=>'30x3']); ?>

          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <input type="submit"  class="form-control btn btn-success"  value="<?php echo app('translator')->getFromJson('fleet.save'); ?>" />
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <button type="button" data-toggle="modal" data-target="#myModal"  class="form-control btn btn-danger"><?php echo app('translator')->getFromJson('fleet.clear_database'); ?></button>  
            </div>
          </div>
        </div>
      </div>
      <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_clear_database'); ?></p>
        <p class="text-danger"><strong><?php echo app('translator')->getFromJson('fleet.note'); ?>: <?php echo app('translator')->getFromJson('fleet.clear_database_note'); ?></strong></p>
      </div>
      <div class="modal-footer">
        <?php echo Form::open(['url' => 'admin/clear-database','method'=>'post']); ?>

        <button class="btn btn-danger" type="submit"><?php echo app('translator')->getFromJson('fleet.clear_database'); ?></button>
        <?php echo Form::close(); ?>

        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!--model 2 -->
<div id="myModal3" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
      <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <img src="" class="myimg">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!--model 2 -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">
  <?php if(Session::get('msg')): ?>
    new PNotify({
        title: 'Success!',
        text: '<?php echo e(Session::get('msg')); ?>',
        type: 'success'
      });
  <?php endif; ?>
  
  $('.view1').click(function(){
    $('#myModal3 .modal-body .myimg').attr( "src","<?php echo e(asset('assets/images/'. Hyvikk::get('icon_img') )); ?>");
    $('#myModal3 .modal-body .myimg').removeAttr( "height");
    $('#myModal3 .modal-body .myimg').removeAttr( "width");
  });

  $('.view2').click(function(){
    $('#myModal3 .modal-body .myimg').attr( "src","<?php echo e(asset('assets/images/'. Hyvikk::get('logo_img') )); ?>");
    $('#myModal3 .modal-body .myimg').attr( "height","140px");
    $('#myModal3 .modal-body .myimg').attr( "width","300px");
  });

  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert('Enter Tax name');
    }
    else{
      $(".blank").append('<div class="row col-md-12"><div class="col-md-4">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <div class="input-group mb-3"><input type="number" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required min=0 step="0.01"> <div class="input-group-append"> <span class="input-group-text fa fa-percent"></span> </div> </div></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/utilities/settings.blade.php ENDPATH**/ ?>
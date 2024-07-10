<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.vendorReport'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
        <?php echo app('translator')->getFromJson('fleet.vendorReport'); ?> &nbsp; <a href="<?php echo e(url('admin/print-vendor-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></a>
        </h3>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-4"></div>
          <div class="col-md-8">
            <?php echo Form::open(['route' => 'reports.vendors','method'=>'post','class'=>'form-inline']); ?>


            <div class="form-group">
              <?php echo Form::label('date1','From'); ?>

              <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('date1', $date1,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'required']); ?>

              </div>
            </div>
            <div class="form-group" style="margin-right: 5px">
              <?php echo Form::label('date2','To'); ?>

              <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                <?php echo Form::text('date2', $date2,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'required']); ?>

              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-info">
              <i class="fa fa-search"></i>
              </button>
            </div>
            <?php echo Form::close(); ?>

          </div>
        </div>
        <div class="row" style="margin-top: 25px;">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="card card-warning">
              <div class="card-body">
                <canvas id="canvas1" width="400" height="300"></canvas>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover" id="myTable">
                    <thead>
                      <tr>
                        <th scope="row"><?php echo app('translator')->getFromJson('fleet.vendor'); ?>:</th>
                        <td><strong><?php echo app('translator')->getFromJson('fleet.total'); ?></strong></td>
                      </tr>
                    </thead>
                    <tbody>
                      <hr>
                      <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <th scope="row"><?php echo e($vendors[$d->vendor_id]); ?></th>
                        <td><?php echo e(Hyvikk::get("currency")); ?> <?php echo e($d->total); ?></td>
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script2"); ?>
<script src="<?php echo e(asset('assets/js/cdn/Chart.bundle.min.js')); ?>"></script>
<script>
window.chartColors = {
  red: 'rgb(255, 99, 132)',
  orange: 'rgb(255, 159, 64)',
  yellow: 'rgb(255, 205, 86)',
  green: 'rgb(75, 192, 192)',
  blue: 'rgb(54, 162, 235)',
  purple: 'rgb(153, 102, 255)',
  grey: 'rgb(201, 203, 207)',
  black: 'rgb(0,0,0)'
};
function random_color(i){
  var color1,color2,color3;
  var col_arr=[];
  for(x=0;x<=i;x++){
    var c1 = [176,255,84,220,134,66,238];
    var c2 = [254,61,147,114,51,26,137];
    var c3 = [27,111,153,93,157,216,187,44,243];
    color1 = c1[Math.floor(Math.random()*c1.length)];
    color2 = c2[Math.floor(Math.random()*c2.length)];
    color3 = c3[Math.floor(Math.random()*c3.length)];

    col_arr.push("rgba("+color1+","+color2+","+color3+",0.5)");
  }
  return col_arr;
}

var chartData = {
  labels: [<?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($vendors[$d->vendor_id]); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
  datasets: [{
      type: 'pie',
      label: "",
      backgroundColor: random_color(<?php echo e(count($details)); ?>),
      borderColor: window.chartColors.black,
      borderWidth: 1,
      data: [<?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($d->total); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>]
  }]
};


window.onload = function() {
  var ctx = document.getElementById("canvas1").getContext("2d");
  window.myMixedChart = new Chart(ctx, {
      type: 'pie',
      data: chartData,
      options: {

          responsive: true,
          title: {
              display: false,
              text: "<?php echo app('translator')->getFromJson('fleet.chart'); ?>"
          },
          tooltips: {
              mode: 'index',
              intersect: true
          }
      }
  });
};
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/moment.js')); ?>"></script>
<!-- bootstrap datepicker -->
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#date1').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    $('#date2').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
  });
</script>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [{
             extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',

                ]}
        ],

        "language": {
                 "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
              }
    });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/vendor.blade.php ENDPATH**/ ?>
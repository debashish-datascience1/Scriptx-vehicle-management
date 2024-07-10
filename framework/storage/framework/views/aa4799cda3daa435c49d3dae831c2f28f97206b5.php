<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#"><?php echo app('translator')->getFromJson('menu.reports'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.monthlyReport'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('fleet.monthlyReport'); ?>
        </h3>
      </div>
      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.monthly','method'=>'post','class'=>'form-inline']); ?>

        <div class="row">
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('year', __('fleet.year'), ['class' => 'form-label']); ?>

            <?php echo Form::select('year', $years, $year_select,['class'=>'form-control']);; ?>

          </div>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('month', __('fleet.month'), ['class' => 'form-label']); ?>

            <?php echo Form::selectMonth('month',$month_select,['class'=>'form-control']);; ?>

          </div>
          <div class="form-group" style="margin-right: 10px">
            <?php echo Form::label('vehicle', __('fleet.vehicles'), ['class' => 'form-label']); ?>

            <select id="vehicle_id" name="vehicle_id" class="form-control vehicles" style="width: 250px">
              <option value=""><?php echo app('translator')->getFromJson('fleet.selectVehicle'); ?></option>
              <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($vehicle->id); ?>" <?php if($vehicle->id == $vehicle_select): ?> selected <?php endif; ?>><?php echo e($vehicle->make); ?>-<?php echo e($vehicle->model); ?>-<?php echo e($vehicle->license_plate); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <button type="submit" class="btn btn-info" style="margin-right: 10px"><?php echo app('translator')->getFromJson('fleet.generate_report'); ?></button>
          <button type="submit" formaction="<?php echo e(url('admin/print-monthly-report')); ?>" class="btn btn-danger"><i class="fa fa-print"></i> <?php echo app('translator')->getFromJson('fleet.print'); ?></button>
        </div>
        <?php echo Form::close(); ?>

      </div>
    </div>
  </div>
</div>

<?php if(isset($result)): ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.report'); ?>
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <div class="card card-warning">
              <div class="card-header">
                <h4 class="card-title"><?php echo app('translator')->getFromJson('fleet.chart'); ?> - <?php echo app('translator')->getFromJson('fleet.income'); ?></h4>
              </div>
              <div class="card-body">
                <canvas id="canvas1" width="400" height="500"></canvas>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover myTable">
                <?php ($income_amt = (is_null($income[0]->income) ? 0 : $income[0]->income)); ?>
                <?php ($expense_amt = (is_null($expenses[0]->expense) ? 0 : $expenses[0]->expense)); ?>
                <thead>
                  <tr>
                    <th scope="row"><?php echo app('translator')->getFromJson('fleet.pl'); ?></th>

                    <td><strong><?php echo e(Hyvikk::get("currency")); ?><?php echo e($income_amt-$expense_amt); ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row"><?php echo app('translator')->getFromJson('fleet.income'); ?></th>
                    <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($income_amt); ?></td>
                    </tr>
                    <tr>
                    <th scope="row"><?php echo app('translator')->getFromJson('fleet.expenses'); ?></th>
                    <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($expense_amt); ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-warning">
              <div class="card-header">
                <h4 class="card-title"><?php echo app('translator')->getFromJson('fleet.chart'); ?> - <?php echo app('translator')->getFromJson('fleet.incomeByCategory'); ?></h4>
              </div>
              <div class="card-body">
                <canvas id="canvas2" width="400" height="500"></canvas>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover myTable">
                <?php ($tot = 0); ?>
                <?php $__currentLoopData = $income_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($tot = $tot + $exp->amount); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <thead>
                  <tr>
                    <th scope="row"><?php echo app('translator')->getFromJson('fleet.incomeByCategory'); ?></th>
                    <td><strong><?php echo e(Hyvikk::get("currency")); ?><?php echo e($tot); ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php $__currentLoopData = $income_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <th scope="row"><?php echo e($income_cats[$exp->income_cat]); ?></th>
                    <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($exp->amount); ?></td>
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-warning">
              <div class="card-header">
                <h4 class="card-title"><?php echo app('translator')->getFromJson('fleet.chart'); ?> - <?php echo app('translator')->getFromJson('fleet.expensesByCategory'); ?></h4>
              </div>

              <div class="card-body">
                <canvas id="canvas3" width="400" height="500"></canvas>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover expTable">
                <thead>
                  <tr>
                    <?php ($tot = 0); ?>
                    <?php $__currentLoopData = $expense_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($tot = $tot + $exp->expense); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <th scope="row"><?php echo app('translator')->getFromJson('fleet.expensesByCategory'); ?></th>
                    <td><strong><?php echo e(Hyvikk::get("currency")); ?><?php echo e($tot); ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php $__currentLoopData = $expense_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <th scope="row">
                    <?php if($exp->type == "s"): ?>
                    <?php echo e($service[$exp->expense_type]); ?>

                    <?php else: ?>
                    <?php echo e($expense_cats[$exp->expense_type]); ?>

                    <?php endif; ?>
                    </th>
                    <td><?php echo e(Hyvikk::get("currency")); ?><?php echo e($exp->expense); ?></td>
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

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
    labels: ["<?php echo app('translator')->getFromJson('fleet.income'); ?>", "<?php echo app('translator')->getFromJson('fleet.expenses'); ?>"],
    datasets: [{
        type: 'pie',
        label: '',
        backgroundColor: [window.chartColors.green,window.chartColors.red],
        borderColor: window.chartColors.black,
        borderWidth: 1,
        data: [<?php echo e(@$income_amt); ?>,<?php echo e(@$expense_amt); ?>]
    }]
};

var chartData2 = {
  labels: [<?php $__currentLoopData = $income_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php echo e($income_cats[$exp->income_cat]); ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
  datasets: [{
      type: 'pie',
      label: '',
      backgroundColor: random_color(<?php echo e(count($income_by_cat)); ?>),
      borderColor: window.chartColors.black,
      borderWidth: 1,
      data: [<?php $__currentLoopData = $income_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($exp->amount); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>]
  }]
};

var chartData3 = {
    labels: [<?php $__currentLoopData = $expense_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> "<?php if($exp->type == "s"): ?><?php echo e($service[$exp->expense_type]); ?> <?php else: ?><?php echo e($expense_cats[$exp->expense_type]); ?><?php endif; ?>", <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>],
    datasets: [{
        type: 'pie',
        label: '',
        backgroundColor: random_color(<?php echo e(count($expense_by_cat)); ?>),
        borderColor: window.chartColors.black,
        borderWidth: 1,
        data: [<?php $__currentLoopData = $expense_by_cat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php echo e($exp->expense); ?>, <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>]
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
    var ctx = document.getElementById("canvas3").getContext("2d");
    window.myMixedChart = new Chart(ctx, {
        type: 'pie',
        data: chartData3,
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

    var ctx = document.getElementById("canvas2").getContext("2d");
    window.myMixedChart = new Chart(ctx, {
        type: 'pie',
        data: chartData2,
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
<?php $__env->startSection("script"); ?>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#vehicle_id').select2();
      $('.myTable').DataTable({
        "paging":   false,
        "ordering": false,
        "searching": false,
        "info":     false,

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

    $('.expTable').DataTable({
      "ordering": false,
      "searching": false,
      "info":     false,
      "pageLength": 5,
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/VehicleMgmt/framework/resources/views/reports/monthly.blade.php ENDPATH**/ ?>
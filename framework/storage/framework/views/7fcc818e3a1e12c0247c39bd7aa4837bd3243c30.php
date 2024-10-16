<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"> <?php echo app('translator')->getFromJson('fleet.fuel'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<style type="text/css">
  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
  .pagination{float: right;}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <?php echo app('translator')->getFromJson('fleet.manageFuel'); ?>
          &nbsp;
          <a href="<?php echo e(route('fuel.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('fleet.addNew'); ?></a>
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="data_table12">
            <thead class="thead-inverse">
              <tr>
                <th>
                  <?php if($data->count() > 0): ?>
                  
                  <?php endif; ?>
                </th>
                <th>SL#</th>
                <th>Vehicle</th>
                <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.fuelType'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.qty'); ?></th>
                <th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
                <th>CGST</th>
                <th>SGST</th>
                <th>Total</th>
                
                <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td>
                  
                </td>
                <td><?php echo e($k+$data->firstItem()); ?></td>
                <td>
                  
                  <strong><?php echo e(date('d-M-Y',strtotime($row->vehicle_data['year']))); ?></strong><br>
                  <a href="<?php echo e(url("admin/vehicles/".$row->vehicle_id."/edit")); ?>" target="_blank">
                  
                  <?php echo e($row->vehicle_data['make']); ?>-<?php echo e($row->vehicle_data['model']); ?>

                  </a>
                  <br>
                  <?php if($row->vehicle_data['vehicle_image'] != null): ?>
                    <a href="<?php echo e(asset('uploads/'.$row->vehicle_data['vehicle_image'])); ?>" target="_blank" class="badge badge-danger"><?php echo e(strtoupper($row->vehicle_data['license_plate'])); ?></a>
                  <?php else: ?>
                    <a href="#" target="_blank" class="badge badge-danger"><?php echo e(strtoupper($row->vehicle_data['license_plate'])); ?></a>
                  <?php endif; ?>
                  <br>
                </td>
                <td>
                  <strong><?php echo e(!empty(Helper::getTransaction($row->id,20)) ? Helper::getTransaction($row->id,20)->transaction_id : ''); ?></strong>
                  <br>
                  <?php echo e(Helper::getCanonicalDate($row->date,'default')); ?>

                  <br>
                  <?php echo e($row->province); ?>

                </td>
                <td>
                  <?php if($row->fuel_details!=''): ?>
                    <?php echo e($row->fuel_details->fuel_name); ?>

                  <?php else: ?>
                    <small style="color:red">specify fuel type</small>
                  <?php endif; ?>
                </td>
                <td> <?php echo e($row->qty); ?> <?php if(Hyvikk::get('fuel_unit') == "gallon"): ?> <?php echo app('translator')->getFromJson('fleet.gal'); ?> <?php else: ?> Liter <?php endif; ?> </td>
                <td>
                  <?php ($total = $row->qty * $row->cost_per_unit); ?>
                  <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($total); ?>

                  <br>
                  <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->cost_per_unit); ?>/ <?php echo e(Hyvikk::get('fuel_unit')); ?>

                </td>
                <td>
                  <?php if(!empty($row->cgst)): ?>
                  <?php echo e($row->cgst); ?> %
                  <br>
                  <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->cgst_amt); ?>

                  <?php endif; ?>
                </td>
                <td>
                  <?php if(!empty($row->sgst)): ?>
                  <?php echo e($row->sgst); ?> %
                  <br>
                  <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->sgst_amt); ?>

                  <?php endif; ?>
                </td>
                <td>
                  <?php if(!empty($row->grand_total)): ?>
                  <?php echo e(Hyvikk::get('currency')); ?> <?php echo e($row->grand_total); ?>

                  <?php endif; ?>
                </td>
                
                
                <td>
                <div class="btn-group">
                  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                    <span class="fa fa-gear"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu custom" role="menu">
                    <a class="dropdown-item vview" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal2"> <span aria-hidden="true" class="fa fa-eye" style="color: #398439;"></span> <?php echo app('translator')->getFromJson('fleet.view'); ?></a>
                    <?php if(Helper::isEligible($row->id,20)): ?>
                    <a class="dropdown-item" href="<?php echo e(url("admin/fuel/".$row->id."/edit")); ?>"> <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> <?php echo app('translator')->getFromJson('fleet.edit'); ?></a>
                    <a class="dropdown-item" data-id="<?php echo e($row->id); ?>" data-toggle="modal" data-target="#myModal"><span aria-hidden="true" class="fa fa-trash" style="color: #dd4b39"></span> <?php echo app('translator')->getFromJson('fleet.delete'); ?></a>
                    <?php endif; ?>
                  </div>
                </div>
                <?php echo Form::open(['url' => 'admin/fuel/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'form_'.$row->id]); ?>

                <?php echo Form::hidden("id",$row->id); ?>

                <?php echo Form::close(); ?>

                </td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            
          </table>
        </div>
        <div class="row">
          <div class="col-md-12 float-right"><?php echo e($data->links()); ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php echo Form::open(['url'=>'admin/delete-fuel','method'=>'POST','id'=>'form_delete']); ?>

        <div id="bulk_hidden"></div>
        <p><?php echo app('translator')->getFromJson('fleet.confirm_bulk_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
        <?php echo Form::close(); ?>

    </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.view'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading..
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.delete'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><?php echo app('translator')->getFromJson('fleet.confirm_delete'); ?></p>
      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit=""><?php echo app('translator')->getFromJson('fleet.delete'); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    $("#form_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });

  $('input[type="checkbox"]').on('click',function(){
    $('#bulk_delete').removeAttr('disabled');
  });

  $('.vview').click(function(){
    // console.log($(this).data("id"));
    var id = $(this).attr("data-id");
    $('#myModal2 .modal-body').load('<?php echo e(url("admin/fuel/view_event")); ?>/'+id,function(result){
      $('#myModal2').modal({show:true});
    });
  });

  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "<?php echo app('translator')->getFromJson('fleet.delete_error'); ?>",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


  $('#chk_all').on('click',function(){
    if(this.checked){
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",true);
      });
    }else{
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",false);
      });
    }
  });

  // Checkbox checked
  function checkcheckbox(){
    // Total checkboxes
    var length = $('.checkbox').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.checkbox').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });
    // console.log(length+" "+totalchecked);
    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#chk_all").prop('checked', true);
    }else{
        $('#chk_all').prop('checked', false);
    }
  }

  // Data Table for this specific page
  //Because it loads so much that it causes timeout.. god damn
  // $(document).ready(function() {
  //   $('#data_tabled tfoot th').each( function () {
  //     // console.log($('#data_tabled tfoot th').length);
  //     if($(this).index() != 0 && $(this).index() != $('#data_tabled tfoot th').length - 1) {
  //       var title = $(this).text();
  //       $(this).html( '<input type="text" placeholder="'+title+'" />' );
  //     }
  //   });

  //   var table = $('#data_tabled').DataTable({
  //     "language": {
  //         "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
  //     },
  //     columnDefs: [ { orderable: false, targets: [0] } ],
  //     // individual column search
  //     "initComplete": function() {
  //               table.columns().every(function () {
  //                 var that = this;
  //                 $('input', this.footer()).on('keyup change', function () {
  //                   // console.log($(this).parent().index());
  //                     that.search(this.value).draw();
  //                 });
  //               });
  //       },
  //       "processing": true,
  //       "serverSide": true,
  //       "ajax":'<?php echo e(route("fuel.getFuelList")); ?>'
  //   });

  //   $('[data-toggle="tooltip"]').tooltip();

  // });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/fuel/index.blade.php ENDPATH**/ ?>
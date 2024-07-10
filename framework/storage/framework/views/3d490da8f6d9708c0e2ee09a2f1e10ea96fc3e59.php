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
  code,pre{background:#eee}
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
          <a class=float-right" data-toggle="modal" data-target="#filterModal"><span aria-hidden="true" class="fa fa-info-circle" style="color: #232756;cursor: pointer;float:right;font-size:32px;margin-top:7px;"></span></a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_tabled">
          <thead class="thead-inverse">
            <tr>
              <th>
                
              </th>
              <th>Vehicle</th>
              <th><?php echo app('translator')->getFromJson('fleet.date'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.fuelType'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.qty'); ?></th>
              <th><?php echo app('translator')->getFromJson('fleet.cost'); ?></th>
              
              
              <th>State</th>
              <th><?php echo app('translator')->getFromJson('fleet.action'); ?></th>
            </tr>
          </thead>
          
          
        </table>
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

<!-- Modal -->
<div id="filterModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Search Codes</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('vehicle_company',"Vehicle's Company", ['class' => 'form-label']); ?>

                    <pre><code>make:ASHOK LEYLAND</code></pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('vehicle_model',"Vehicle's Model", ['class' => 'form-label']); ?>

                    <pre><code>model:1412 E4/5</code></pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('license_plate',"License Plate", ['class' => 'form-label']); ?>

                    <pre><code>license:OD12C2671</code></pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('fuel_date',"Fuel Date", ['class' => 'form-label']); ?>

                    <pre><code>date:04-06-2021</code></pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('fuel_type',"Fuel Type", ['class' => 'form-label']); ?>

                    <pre><code>fuel:Petrol</code></pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php echo Form::label('state',"State", ['class' => 'form-label']); ?>

                    <pre><code>state:Odisha</code></pre>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

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
  $(document).ready(function() {
    $('#data_tabled tfoot th').each( function () {
      // console.log($('#data_tabled tfoot th').length);
      if($(this).index() != 0 && $(this).index() != $('#data_tabled tfoot th').length - 1) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
      }
    });

    var table = $('#data_tabled').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":'<?php echo e(route("fuel.getFuelList")); ?>',
        "columns":[
            {data : 'id'},
            {data : 'vehicle_id'},
            {data : 'date'},
            {data : 'fuel_type'},
            {data : 'qty'},
            {data : 'start_meter'},
            {data : 'province'},
            {data : 'action'},
            // {data : 'date'}
        ],
      "language": {
          "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
      },
      columnDefs: [ { orderable: false, targets: [0] } ],
      // individual column search
      "initComplete": function() {
                $("#data_tabled input").unbind();
                $("#data_tabled input").bind('keyup',function(e){
                    if(e.keyCode==13)
                        table.fnFilter(this.value);
                });
                table.columns().every(function () {
                  var that = this;
                  $('input', this.footer()).on('keyup change', function () {
                    console.log($(this).parent().index());
                    console.log(this.value)
                      that.search(this.value).draw();
                  });
                });
        },
    });

    

    $('[data-toggle="tooltip"]').tooltip();

  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/fuel/index-datatable.blade.php ENDPATH**/ ?>
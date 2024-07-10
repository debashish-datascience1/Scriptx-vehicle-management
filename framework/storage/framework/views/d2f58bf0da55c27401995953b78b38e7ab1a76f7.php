<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">
<style type="text/css">
  .mybtn1
  {
   padding-top: 4px;
    padding-right: 8px;
    padding-bottom: 4px;
    padding-left: 8px;
  }

  .checkbox, #chk_all{
    width: 20px;
    height: 20px;
  }
  .where_from,.advance_for{cursor: pointer;}
  .where_from{color:#fff!important; }
  .border-refund{border:2px solid #02bcd1; }
  .badge-driver-adv{background: royalblue;color:#fff;}
  .badge-parts{background: darkslategrey;color:#fff;}
  .badge-refund{background: darkviolet;color:#fff;}
  .badge-fuel{background: #8bc34a;color:#fff;}
  .badge-starting-amt{background: #c34a4a;color:#fff;}
  .badge-deposit{background: #b000bb;color:#fff;}
  .badge-revised{background: #da107f;color:#fff;}
  .badge-liability{background: #004e5c;color:#fff;}
  .badge-viwevent{background: #0091bd;color:#fff!important;cursor: pointer;}
  .btn-search{transition: .7s}
  .btn-search:hover{background:#9c27b0;border:1px solid white}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('fleet.transactions'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <?php if(count($errors) > 0): ?>
    <div class="alert alert-danger">
      <ul>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
    <?php endif; ?>
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Bank <?php echo app('translator')->getFromJson('fleet.transactions'); ?> &nbsp;
          <a href="<?php echo e(route("transaction.search")); ?>" class="btn btn-danger btn-search float-right" target="_blank"><span class="fa fa-search fa-lg"></span> &nbsp;<strong>Search </strong> </a>
        </h3>
      </div>

      <div class="card-body table-responsive">
        <table class="table" id="data_tabled" style="padding-bottom: 15px">
          <thead class="thead-inverse">
            <tr>
              <th>
                
              </th>
              <th>Transaction ID</th>
              <th>From</th>
              <th>Method</th>
              <th>Previous</th>
              <th>Total</th>
              <th>Remaining</th>
              <th>Grand Total</th>
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
        <?php echo Form::open(['url'=>'admin/delete-drivers','method'=>'POST','id'=>'form_delete']); ?>

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
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="viewModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.view'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="adjustModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 43%">
    <!-- Modal content-->
    <form action="<?php echo e(route('accounting.store')); ?>" method="POST">
    <?php echo e(csrf_field()); ?>

      <div class="modal-content" style="width: 150%">
        <div class="modal-header">
          <h4 class="modal-title">Adjusting Transaction</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Loading...
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success adjustSubmit" value="Submit">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal -->
<div id="whereModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        Loading....
      </div>
  </div>
</div>

<!-- Modal -->
<div id="advanceForModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Advance Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          Loading...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?>
          </button>
        </div>
      </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
  $("body").on("click",".where_from",function(){
    var id = $(this).data("id");
    var partsw = $(this).data("partsw");
    // console.log(id);
    // console.log(partsw);
    $("#whereModal .modal-content").load('<?php echo e(url("admin/accounting/where_from")); ?>/'+id,function(res){
      typeof partsw!="undefined" ? $(this).css('width','160%') : $(this).css('width','');
      $("#whereModal").modal({show:true})
    })
  })

  $("body").on("click",".advance_for",function(){
    var id = $(this).data("id");
    $("#advanceForModal .modal-body").load('<?php echo e(url("admin/accounting/advance_for")); ?>/'+id,function(res){
      $("#advanceForModal").modal({show:true})
    })
  })

  $("body").on("click",".vevent",function(){
    var id = $(this).data("id");
    // console.log(id)
      $("#viewModal .modal-body").load('<?php echo e(url("admin/accounting/view_event")); ?>/'+id,function(res){
        // console.log(res)
        $("#viewModal").modal({show:true});
      })
  })

  $('#changepass').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#driver_id").val(id);
  });

  $("#changepass_form").on("submit",function(e){
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
       new PNotify({
            title: 'Success!',
            text: "<?php echo app('translator')->getFromJson('fleet.passwordChanged'); ?>",
            type: 'info'
        });
      },
      dataType: "html"
    });
    $('#changepass').modal("hide");
    e.preventDefault();
  });


  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
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
    "ajax":'<?php echo e(route("accounting.getTransactionBankList")); ?>',
    "columns":[
        {data : 'id'},
        {data : 'trash_id'},
        {data : 'from'},
        {data : 'method'},
        {data : 'prev'},
        {data : 'total'},
        {data : 'remaining'},
        {data : 'gtotal'},
        // {data : 'date'}
    ],
    "language": {
        "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
    },
    "searching":false,
    columnDefs: [ { orderable: true, targets: [0] } ],
    // "order":[[0,"asc"]],
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

// $(function(){
//     $(".btn-search ").hover(function(){
//         $(this).css("background",'hotpink').fadeIn(1000);
//     })
// })

  
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/scripyat/public_html/scriptx.in/VehicleMgmt/framework/resources/views/transactions/index-bank-datatable.blade.php ENDPATH**/ ?>
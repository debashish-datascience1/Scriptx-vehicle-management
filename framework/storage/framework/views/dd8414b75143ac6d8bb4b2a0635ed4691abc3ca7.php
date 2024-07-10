<?php ($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y'); ?>

<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="#">Global Search</a></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('extra_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-datepicker.min.css')); ?>">

<style type="text/css">
.form-label{display:block !important;}
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
    
    .fullsize{width: 100% !important;}
	  .newrow{margin: 0 auto;width: 100%;margin-bottom: 15px;}
	  .dateShow{padding-right: 13px}
    .check{color: green;font-size: 15px;}
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }
  </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Global Search
        </h3>
      </div>

      
      <div class="card-body">
        <?php echo Form::open(['route' => 'reports.global-search','method'=>'post','class'=>'form-block']); ?>

        <div class="row newrow">
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('param_id', 'Search', ['class' => 'form-label']); ?>

              <?php echo Form::select('param_id',$params,$request['param_id'] ?? null,['class'=>'form-control fullsize','id'=>'param_id']); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('vehicle_no', 'Vehicle(s)', ['class' => 'form-label']); ?>

              <?php echo Form::select('vehicle_no[]',$vehicles,$request['vehicle_no'] ?? null,['class'=>'form-control fullsize','id'=>'vehicle_no','multiple'=>'multiple','placeholder'=>'ALL','required']); ?>

              <i><small>Enter vehicle license plate</small></i>
            </div>
          </div>
          <div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('from_date','From',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
								<div class="input-group-prepend">
								<span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
								<?php echo Form::text('from_date',$request['from_date'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.start_date'),'readonly']); ?>

							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<?php echo Form::label('to_date','To',['class' => 'form-label dateShow']); ?>

							<div class="input-group">
							  <div class="input-group-prepend">
							  <span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
							  <?php echo Form::text('to_date', $request['to_date'] ?? null,['class' => 'form-control','placeholder'=>__('fleet.end_date'),'readonly']); ?>

							</div>
						</div>
					</div>
        </div>
        <div class="row newrow">
          <div class="col-md-12">
            <button type="submit" class="btn btn-info gen_report" style="margin-right: 10px">Search</button>
            
          </div>
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
          <?php echo count($collection)>1 ? "Search Results" : "Search Result"; ?>

        </h3>
      </div>
      <div class="card-body table-responsive">
        <?php if($param_post==18): ?>
          <?php echo $__env->make('reports.global_search.bookings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif($param_post==20): ?>
          <?php echo $__env->make('reports.global_search.fuel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
        
      </div>
    </div>
  </div>
</div>

<?php endif; ?>


<!-- single delete Modal -->
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
<!-- single delete Modal -->


<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo app('translator')->getFromJson('fleet.viewBookingDetails'); ?></h4>
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


<div class="modal fade" id="modalComplete" tabindex="-1" role="dialog" aria-labelledby="modalCompleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCompleteLabel">Complete Booking Process</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalRoute" role="dialog" aria-labelledby="modalRouteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRouteLabel">Add Route</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDriverAdvanceLater" role="dialog" aria-labelledby="modalDriverAdvanceLaterLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width:150%;">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDriverAdvanceLaterLabel">Add Late Driver Advance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Loading...
      </div>
    </div>
  </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#user_id").select2();
	});
</script>

<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/jszip.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/pdfmake.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/vfs_fonts.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap-datepicker.min.js')); ?>"></script>
<script type="text/javascript">
function isNumber(evt, element) {
      var charCode = (evt.which) ? evt.which : event.keyCode
      if (            
          (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
          (charCode < 48 || charCode > 57))
          return false;
          return true;
  }
$(document).ready(function() {
  $("#vehicle_no").select2();
  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");
    var param_id = $("#param_id").val();
    // console.log(param_id);return false;
    param_id==20 ? $("#form_"+id).submit() : $("#book_"+id).submit();
  });
  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#del_btn").attr("data-submit",id);
  });
	$('#myTable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    });
    var myTable = $('#myTable').DataTable({
      // dom: 'Bfrtip',
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
            },
      "initComplete": function() {
              myTable.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    that.search(this.value).draw();
                });
              });
            }

    });

    // Dates
    $('#from_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $('#to_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
    });

    // Complete Modal
  $(".vcomplete").click(function(){
    var id = $(this).data('id');
    $("#modalComplete .modal-body").load('<?php echo e(url("admin/bookings/modalcomplete")); ?>/'+id,function(result){
      $("#modalComplete").modal({show:true, backdrop:'static',keyboard:false})
    })
  })
  // Route Modal
  $(".vRoute").click(function(){
    var id = $(this).data('id');
    $("#modalRoute .modal-body").load('<?php echo e(url("admin/bookings/modalroute")); ?>/'+id,function(result){
      $("#modalRoute").modal({show:true, backdrop:'static',keyboard:false})
      $(".next_booking").select2();
    })
  })
  // Late Driver Advance Modal
  $(".vDriverAdvanceLater").click(function(){
    var id = $(this).data('id');
    $("#modalDriverAdvanceLater .modal-body").load('<?php echo e(url("admin/bookings/modal-late-driver-advance")); ?>/'+id,function(result){
      $("#modalDriverAdvanceLater").modal({show:true, backdrop:'static',keyboard:false})
      // $(".next_booking").select2();
    })
  })

  $("body").on("click",".submit",function(){
    var advance_date = $(".advance_date").val();
    // alert(12);
    if(advance_date==''){
      alert('Please select date');
      $(".advance_date").focus();
      return false;
    }
  })

  $("body").on("focus",".advance_date",function(){
    $(this).datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  })

  $("body").on("keyup",".fodder_km",function(){
    console.log($(this).val())
    var fodder_km = $(this).val();
    var vehicle_id = $("#vehicle_id").val();
    var sendData = {_token:"<?php echo e(csrf_token()); ?>",fodder_km:fodder_km,vehicle_id:vehicle_id};
    $.post('<?php echo e(route("bookings.getMileageDate")); ?>',sendData).done(function(result){
      console.log(result);
      $(".ajax-class th").html(result.view);
      $(".ajax-class").show();
    })
  })

  $('.vbook').click(function(){
    // alert($(this).data("id"));
    var id = $(this).attr("data-id");
    // alert('<?php echo e(url("admin/vehicle/event")); ?>/'+id)
    $('#myModal2 .modal-body').load('<?php echo e(url("admin/bookings/event")); ?>/'+id,function(result){
      // console.log(result);
      $('#myModal2').modal({show:true});
      if($('.adexist').length) $("#myModal2 .modal-content").css('width','111%');
      else $("#myModal2 .modal-content").css('width','100%');
    });
  });

  $('.vview').click(function(){
    // console.log($(this).data("id"));
    var id = $(this).attr("data-id");
    $('#myModal2 .modal-body').load('<?php echo e(url("admin/fuel/view_event")); ?>/'+id,function(result){
      $('#myModal2').modal({show:true});
    });
  });

  $("body").on("click",".google_calculate",function(){
      //validate the next reference booking selected or not
      var blankTest = /\S/;
      var booking_id = $("#book_id").val();
      var next_booking = $("#next_booking").val();
      if(!blankTest.test(booking_id) || !blankTest.test(next_booking)){
        alert("Please select next reference booking");
      }
      var data={_token:"<?php echo e(csrf_token()); ?>",booking_id:booking_id,next_booking:next_booking};
      console.log(booking_id,next_booking)
      $.post("<?php echo e(url('admin/bookings/get_distanecfromaddress')); ?>",data).done(function(result){
        console.log(result);
        if(result.status=="OK"){
          //redo this.
          var obj = result.rows[0].elements[0];
          if(obj.status=="OK"){
            var distance = obj.distance.value/1000;
            $("#fodder_km").val(distance);
            $(".google_time").html("Distance : "+obj.duration.text)
            $(".google_error").html("");
            $("#fodder_km").trigger('keyup');
          }else{
            $("#fodder_km").val('');
            $(".google_time").html('');
            $("tr.ajax-class th").html('');
            $(".google_error").html("Something went wrong. Please enter distance manually.");
          }
        }else{
          $("#fodder_km").val('');
          $(".google_time").html('');
          $("tr.ajax-class th").html('');
          $(".google_error").html("Something went wrong. Please enter distance manually.");
        }
      })
    })

    $(document).on('keyup','#toll_tax,#food,#labour,#advance,#others,#refund,#tyre,#donations,#documents,#fuel,#maintenance,#electrical',function(){
    var total = $("#total_adv").val();
    var toll_tax = $("#toll_tax").val()!='' ? $("#toll_tax").val() : 0;
    var food = $("#food").val()!='' ? $("#food").val() : 0;
    var labour = $("#labour").val()!='' ? $("#labour").val() : 0;
    var advance = $("#advance").val()!='' ? $("#advance").val() : 0;
    var refund = $("#refund").val()!='' ? $("#refund").val() : 0;
    var tyre = $("#tyre").val()!='' ? $("#tyre").val() : 0;
    var donations = $("#donations").val()!='' ? $("#donations").val() : 0;
    var documents = $("#documents").val()!='' ? $("#documents").val() : 0;
    var fuel = $("#fuel").val()!='' ? $("#fuel").val() : 0;
    var maintenance = $("#maintenance").val()!='' ? $("#maintenance").val() : 0;
    var electrical = $("#electrical").val()!='' ? $("#electrical").val() : 0;
    // console.log($(this).next().attr('class'));
    // console.log($(this).next());
    if($(this).val()!="" && $(this).next().attr('class')!='prem'){
      var alhead = $(this).attr('name');
      // console.log(alhead);
      $("<div class='prem'><textarea name='remarks["+alhead+"]' class='form-control remarks' style='resize:none;height:100px;margin-top:10px;' placeholder='Remarks...'></textarea></div>").insertAfter($(this));
    }
    if($(this).val()=="")
      $(this).next().remove('.prem');

    

    var gtotal = parseInt(toll_tax)+parseInt(food)+parseInt(labour)+parseInt(advance)+parseInt(refund)+parseInt(tyre)+parseInt(donations)+parseInt(documents)+parseInt(fuel)+parseInt(maintenance)+parseInt(electrical);
    var remain = (total-gtotal);
    
    if(total<gtotal){
      $(this).val('');
    // reserting others
      var othr = {_token:'<?php echo e(csrf_token()); ?>',total:total};
      $('.from-input').each(function(){
        var self = $(this);
        var inputName = self.attr("name");
        if(self.val()!=null || self.val()!=0 || self.val()!=0.00 || self.val()!='undefined')
        othr[inputName] = self.val();
        // othr.push(self.val());
      })
      var posted = $.post('<?php echo e(url("admin/bookings/markascomplete/others")); ?>',othr);
      posted.done(function(res){
        console.log(res);
        $("#others").val(res.value);
      })
      // console.log(othr)
      // $("#others").val(othr);
      $(this).next().remove('.prem');
      $(this).focus();
    }else{
      $("#others").val(remain);
      if($("#others").val()!=0)
        $("#others").next().prop('readonly',false);
      else
        $("#others").next().prop('readonly',true);
      

      // console.log(remain);
      // console.log(typeof remain);
    }
  })

});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/reports/global_search/global-search.blade.php ENDPATH**/ ?>
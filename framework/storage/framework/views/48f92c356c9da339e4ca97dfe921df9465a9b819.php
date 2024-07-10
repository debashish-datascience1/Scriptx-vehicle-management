<?php $__env->startSection("extra_css"); ?>

<style type="text/css">
  .modal-body{
    height: 400px;
    overflow-y: auto;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection("breadcrumb"); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route("bookings.index")); ?>"><?php echo app('translator')->getFromJson('menu.bookings'); ?></a></li>
<li class="breadcrumb-item active"><?php echo app('translator')->getFromJson('menu.calendar'); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><?php echo app('translator')->getFromJson('menu.calendar'); ?></h3>
      </div>
      <div class="card-body">
        <div id='calendar'></div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="booking_detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel"><?php echo app('translator')->getFromJson('fleet.event_details'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="my_event" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel1"><?php echo app('translator')->getFromJson('fleet.my_events'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->getFromJson('fleet.close'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("script"); ?>
<!-- fullCalendar 2.2.5 -->
<script src="<?php echo e(asset('assets/js/new_moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/fullcalendar/fullcalendar.min.js')); ?>"></script>
<?php if(Hyvikk::get('language')!="English-en"): ?>
  <?php ($lg = explode('-',Hyvikk::get('language'))); ?>
  <?php if($lg[1]!='al'): ?>
    <script src="<?php echo e(asset('assets/js/cdn/calendar/'.$lg[1].'.js')); ?>"></script>
  <?php endif; ?>
<?php endif; ?>
<script>
  $(document).ready(function() {
    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },

      defaultDate: '<?php echo e(date("Y-m-d")); ?>',
      navLinks: true, // can click day/week names to navigate views
      editable: false,
      events: "<?php echo e(url('admin/calendar')); ?>",
      eventLimit: true,
      eventClick: function(calEvent, jsEvent, view) {
         $.ajax({

            url: '<?php echo e(url("/admin/calendar/event/")); ?>/'+calEvent.type+'/'+calEvent.id,

          })
          .then(function(content){
            $("#booking_detail .modal-body").empty();
            $("#booking_detail .modal-body").html(content);
            $("#booking_detail").modal("show");

          },
          function(xhr, status, error) {

            api.set('content.text', status + ': ' + error);
          });
        }
    });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/bookings/calendar.blade.php ENDPATH**/ ?>
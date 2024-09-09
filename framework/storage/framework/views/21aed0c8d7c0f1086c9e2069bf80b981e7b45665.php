<!DOCTYPE html>
<?php if(Auth::user()->getMeta('language')!= null): ?>
  <?php ($language = Auth::user()->getMeta('language')); ?>
<?php else: ?>
  <?php ($language = Hyvikk::get("language")); ?>
<?php endif; ?>


<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title><?php echo e(Hyvikk::get('app_name')); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?php echo e(asset('favicon1.ico')); ?>" type="image/x-icon">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/font-awesome/css/font-awesome.min.css')); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/ionicons.min.css')); ?>">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/fullcalendar/fullcalendar.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/fullcalendar/fullcalendar.print.css')); ?>" media="print">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/datatables/dataTables.bootstrap4.min.css')); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/buttons.dataTables.min.css')); ?>">
    <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/select2/select2.min.css')); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/css/dist/adminlte.min.css')); ?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/iCheck/flat/blue.css')); ?>">
    <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/iCheck/all.css')); ?>">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/morris/morris.css')); ?>">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')); ?>">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')); ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="<?php echo e(asset('assets/css/fonts/fonts.css')); ?>" rel="stylesheet">
  <link href="<?php echo e(asset('assets/css/pnotify.custom.min.css')); ?>" media="all" rel="stylesheet" type="text/css" />
  <?php echo $__env->yieldContent("extra_css"); ?>
  <script>
  window.Laravel = <?php echo json_encode([
  'csrfToken' => csrf_token(),
  'subscription_url' => asset('assets/push_notification/push_subscription.php'),
  'serviceWorkerUrl' => asset("serviceWorker.js")
  ]); ?>;
  </script>
  <!-- browser notification -->
  <script type="text/javascript" src="<?php echo e(asset('assets/push_notification/app.js')); ?>"></script>
  
  <style type="text/css">
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
        font-size: 0.6em;
        height: 35px !important;
    }
    .cursor-pointer{cursor: pointer;}
  </style>
  <?php if($language == "Arabic-ar"): ?>
    <style type="text/css">
      .sidebar{
        text-align: right;
      }
      .nav-sidebar .nav-link>p>.right {
        position: absolute;
        right: 0rem;
        top: 12px;
      }
      .nav-sidebar>.nav-item {
        margin-right: -20px;
      }
    </style>
  <?php endif; ?>
</head>

<body class="hold-transition sidebar-mini" <?php if($language == "Arabic-ar"): ?> dir="rtl" <?php endif; ?>>
  <?php echo Form::hidden('loggedinuser',Auth::user()->id,['id'=>'loggedinuser']); ?>

  <?php echo Form::hidden('user_type',Auth::user()->user_type,['id'=>'user_type']); ?>

<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
          <?php if(Auth::user()->user_type == "S"): ?>
            <?php ($r = 0); ?>
            <?php ($i = 0); ?>
            <?php ($l = 0); ?>
            <?php ($d = 0); ?>
            <?php ($s = 0); ?>
            <?php ($user= Auth::user()); ?>
            <?php $__currentLoopData = $user->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($notification->type == "App\Notifications\RenewRegistration"): ?>
              <?php ($r++); ?>
              <?php elseif($notification->type == "App\Notifications\RenewInsurance"): ?>
              <?php ($i++); ?>
              <?php elseif($notification->type == "App\Notifications\RenewVehicleLicence"): ?>
              <?php ($l++); ?>
              <?php elseif($notification->type == "App\Notifications\RenewDriverLicence"): ?>
              <?php ($d++); ?>
              <?php elseif($notification->type == "App\Notifications\ServiceReminderNotification"): ?>
              <?php ($s++); ?>
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php ($n = $r + $i +$l + $d + $s); ?>
      
      <?php endif; ?>
    <!-- logout -->
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user-circle"></i>
          <span class="badge badge-danger navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <?php if(Auth::user()->user_type == 'D' && Auth::user()->getMeta('driver_image') != null): ?>
              <?php if(starts_with(Auth::user()->getMeta('driver_image'),'http')): ?>
                <?php ($src = Auth::user()->getMeta('driver_image')); ?>
                <?php else: ?>
                <?php ($src=asset('uploads/'.Auth::user()->getMeta('driver_image'))); ?>
                <?php endif; ?>
                <img src="<?php echo e($src); ?>" class="img-size-50 mr-3 img-circle" alt="User Image">
                <?php elseif(Auth::user()->user_type == 'S' || Auth::user()->user_type == 'O'): ?>
                  <?php if(Auth::user()->getMeta('profile_image') == null): ?>
                  <img src="<?php echo e(asset("assets/images/no-user.jpg")); ?>" class="img-size-50 mr-3 img-circle" alt="User Image">
                  <?php else: ?>
                  <img src="<?php echo e(asset('uploads/'.Auth::user()->getMeta('profile_image'))); ?>" class="img-size-50 mr-3 img-circle" alt="User Image">
                  <?php endif; ?>
                <?php elseif(Auth::user()->user_type == 'C' && Auth::user()->getMeta('profile_pic') != null): ?>
                <?php if(starts_with(Auth::user()->getMeta('profile_pic'),'http')): ?>
                <?php ($src = Auth::user()->getMeta('profile_pic')); ?>
                <?php else: ?>
                <?php ($src=asset('uploads/'.Auth::user()->getMeta('profile_pic'))); ?>
                <?php endif; ?>
                <img src="<?php echo e($src); ?>" class="img-size-50 mr-3 img-circle" alt="User Image">
                <?php else: ?>
                <img src="<?php echo e(asset("assets/images/no-user.jpg")); ?>" class="img-size-50 mr-3 img-circle" alt="User Image">
                <?php endif; ?>

              <div class="media-body">
                <h3 class="dropdown-item-title">
                  <?php echo e(Auth::user()->name); ?>


                  <span class="float-right text-sm text-danger">

                  </span>
                </h3>
                <p class="text-sm text-muted"><?php echo e(Auth::user()->email); ?></p>
                <p class="text-sm text-muted"></p>

              </div>
            </div>
            <div>
            <div style="margin: 5px;">
              <a href="<?php echo e(url('admin/change-details/'.Auth::user()->id)); ?>" class="btn btn-secondary btn-flat"><i class="fa fa-edit"></i> <?php echo app('translator')->getFromJson('fleet.editProfile'); ?></a>

              <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-secondary btn-flat pull-right"> <i class="fa fa-sign-out"></i>
              <?php echo app('translator')->getFromJson('menu.logout'); ?>
              </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo e(csrf_field()); ?>

            </form>
            </div>
            <div class="clear"></div>
            </div>
            <!-- Message End -->
          </a>
        </div>
      </li>
      
    <!-- logout -->
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo e(url('admin/')); ?>" class="brand-link">
      <img src="<?php echo e(asset('assets/images/'. Hyvikk::get('icon_img') )); ?>" alt="Fleet Logo" class="brand-image"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo e(Hyvikk::get('app_name')); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
           <?php if(Auth::user()->user_type == 'D' && Auth::user()->getMeta('driver_image') != null): ?>
           <?php if(starts_with(Auth::user()->getMeta('driver_image'),'http')): ?>
            <?php ($src = Auth::user()->getMeta('driver_image')); ?>
            <?php else: ?>
            <?php ($src=asset('uploads/'.Auth::user()->getMeta('driver_image'))); ?>
            <?php endif; ?>
            <img src="<?php echo e($src); ?>" class="img-circle elevation-2" alt="User Image">
            <?php elseif(Auth::user()->user_type == 'S' || Auth::user()->user_type == 'O'): ?>
              <?php if(Auth::user()->getMeta('profile_image') == null): ?>
              <img src="<?php echo e(asset("assets/images/no-user.jpg")); ?>" class="img-circle elevation-2" alt="User Image">
              <?php else: ?>
              <img src="<?php echo e(asset('uploads/'.Auth::user()->getMeta('profile_image'))); ?>" class="img-circle elevation-2" alt="User Image">
              <?php endif; ?>
            <?php elseif(Auth::user()->user_type == 'C' && Auth::user()->getMeta('profile_pic') != null): ?>
            <?php if(starts_with(Auth::user()->getMeta('profile_pic'),'http')): ?>
            <?php ($src = Auth::user()->getMeta('profile_pic')); ?>
            <?php else: ?>
            <?php ($src=asset('uploads/'.Auth::user()->getMeta('profile_pic'))); ?>
            <?php endif; ?>
            <img src="<?php echo e($src); ?>" class="img-circle elevation-2" alt="User Image">
            <?php else: ?>
            <img src="<?php echo e(asset("assets/images/no-user.jpg")); ?>" class="img-circle elevation-2" alt="User Image">
            <?php endif; ?>

        </div>
        <div class="info">
          <a href="<?php echo e(url('admin/change-details/'.Auth::user()->id)); ?>" class="d-block"><?php echo e(Auth::user()->name); ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!-- customer -->
          <?php if(Auth::user()->user_type=="C"): ?>

            <?php if(Request::is('admin/bookings*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>
            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-address-card"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.bookings'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('bookings.create')); ?>" class="nav-link <?php if(Request::is('admin/bookings/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-address-book nav-icon "></i>
                  <p> <?php echo app('translator')->getFromJson('menu.newbooking'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bookings.index')); ?>" class="nav-link <?php if((Request::is('admin/bookings*')) && !(Request::is('admin/bookings/create')) && !(Request::is('admin/bookings_calendar'))): ?> active <?php endif; ?>">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('menu.manage_bookings'); ?></p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(url('admin/change-details/'.Auth::user()->id)); ?>" class="nav-link <?php if(Request::is('admin/change-details*')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-edit"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.editProfile'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(url('admin/addresses')); ?>" class="nav-link <?php if(Request::is('admin/addresses*')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-map-marker"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.addresses'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(url('admin/')); ?>" class="nav-link <?php if(Request::is('admin')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-money"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.expenses'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <?php endif; ?>
          <!-- customer -->
          <!-- user-type S or O -->
          <?php if(Auth::user()->user_type=="S" || Auth::user()->user_type=="O"): ?>
          <li class="nav-item">
            <a href="<?php echo e(url('admin/')); ?>" class="nav-link <?php if(Request::is('admin')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.Dashboard'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('reports.global-search')); ?>" class="nav-link <?php if(Request::is('admin/global-search')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-globe"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.global_search'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          
          <?php endif; ?>
          <!-- user-type S or O -->

          <!-- driver -->
          <?php if(Auth::user()->user_type=="D"): ?>

          <li class="nav-item">
            <a href="<?php echo e(url('admin/')); ?>" class="nav-link <?php if(Request::is('admin/')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-user"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.myProfile'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(route('my_bookings')); ?>" class="nav-link <?php if(Request::is('admin/my_bookings')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-book"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.my_bookings'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo e(url('admin/change-details/'.Auth::user()->id)); ?>" class="nav-link <?php if(Request::is('admin/change-details*')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-edit"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.editProfile'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li>
            <?php if(Request::is('admin/notes*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.notes'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('notes.index')); ?>" class="nav-link <?php if((Request::is('admin/notes*') && !(Request::is('admin/notes/create')))): ?> active <?php endif; ?>">
                  <i class="fa fa-flag nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('fleet.manage_note'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route("notes.create")); ?>" class="nav-link <?php if(Request::is('admin/notes/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.create_note'); ?></p>
                </a>
              </li>
            </ul>
          </li>
            <?php if(Request::is('admin/driver-reports*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-book"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.reports'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route("dreports.monthly")); ?>" class="nav-link <?php if(Request::is('admin/driver-reports/monthly')): ?> active <?php endif; ?>">
                  <i class="fa fa-calendar nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.monthlyReport'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route("dreports.yearly")); ?>" class="nav-link <?php if(Request::is('admin/driver-reports/yearly')): ?> active <?php endif; ?>">
                  <i class="fa fa-calendar nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.yearlyReport'); ?></p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>
          <!-- driver -->

          <!-- sidebar menus for office-admin and super-admin -->
        <?php if(Auth::user()->user_type == "S" || Auth::user()->user_type == "O"): ?>
        <?php ($modules=unserialize(Auth::user()->getMeta('module'))); ?> <!--array of selected modules of logged in user-->
        <?php else: ?>
        <?php ($modules=array()); ?>
        <?php endif; ?>

        <?php if(!Auth::guest() &&  Auth::user()->user_type!="D" && Auth::user()->user_type != "C" ): ?>

            <?php if((Request::is('admin/drivers*')) || (Request::is('admin/users*')) || (Request::is('admin/customers*')) ): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(0,$modules) || Auth::user()->user_type == "S"): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-users"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.users'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if(in_array(0,$modules)): ?>
              <li class="nav-item">
                <a href="<?php echo e(route('drivers.index')); ?>" class="nav-link <?php if(Request::is('admin/drivers*')): ?> active <?php endif; ?>">
                  <i class="fa fa-id-card nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.drivers'); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if(Auth::user()->user_type=="S"): ?>
              <li class="nav-item">
                <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php if(Request::is('admin/users*')): ?> active <?php endif; ?>">
                  <i class="fa fa-user nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.users'); ?></p>
                </a>
              </li>
              <?php if(in_array(0,$modules)): ?>
              <li class="nav-item">
                <a href="<?php echo e(route('customers.index')); ?>" class="nav-link <?php if(Request::is('admin/customers*')): ?> active <?php endif; ?>">
                  <i class="fa fa-address-card nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.customers'); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php endif; ?>
            </ul>
          </li> <?php endif; ?>
          <?php if((Request::is('admin/payroll*')) || Request::is('admin/manage-payroll*') || (Request::is('admin/leave*')) || Request::is('admin/bulk_leave*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(1,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-user-circle-o"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.hrms'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('payroll.index')); ?>" class="nav-link <?php if(Request::is('admin/payroll*')): ?> active <?php endif; ?>">
                  <i class="fa fa-inr nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.payroll'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('payroll.managepayroll')); ?>" class="nav-link <?php if(Request::is('admin/manage-payroll*')): ?> active <?php endif; ?>">
                  <i class="fa fa-th-list nav-icon"></i>
                  <p>Manage Payroll</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('leave.index')); ?>" class="nav-link <?php if(Request::is('admin/leave*') && !Request::is('admin/leave/report')): ?> active <?php endif; ?>">
                  <i class="fa fa-paperclip nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.leave'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bulk_leave.index')); ?>" class="nav-link <?php if(Request::is('admin/bulk_leave*')): ?> active <?php endif; ?>">
                  <i class="fa fa-thumb-tack nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.bulk_leave'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>
          <?php if(Request::is('admin/accounting*') || Request::is('admin/bank-account*') || Request::is('admin/ob-balance*') ||  Request::is('admin/fastag*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
          <?php endif; ?>
          <?php if(in_array(1,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-money"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.accounting'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('bank-account.index')); ?>" class="nav-link <?php if(Request::is('admin/bank-account*') &&  !Request::is('admin/bank-account/bulk_pay') && !Request::is('admin/bank-account/bulk_receive') && !Request::is('admin/bank-account/bulk_pay/manage') && !Request::is('admin/bank-account/deposit*')): ?> active <?php endif; ?>">
                  <i class="fa fa-university nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.bankAccount'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bank-account.deposit')); ?>" class="nav-link <?php if(Request::is('admin/bank-account/deposit*')  &&  !Request::is('admin/bank-account/bulk_pay') && !Request::is('admin/bank-account/bulk_pay/manage')): ?> active <?php endif; ?>">
                  <i class="fa fa-sticky-note-o nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.deposit'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bank-account.bulk_pay')); ?>" class="nav-link <?php if(Request::is('admin/bank-account/bulk_pay')): ?> active <?php endif; ?>">
                  <i class="fa fa-list-ol nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.bulkPay'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bank-account.bulk_receive')); ?>" class="nav-link <?php if(Request::is('admin/bank-account/bulk_receive')): ?> active <?php endif; ?>">
                  <i class="fa fa-list-ul nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.bulkReceive'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bulk_pay.manage')); ?>" class="nav-link <?php if(Request::is('admin/bank-account/bulk_pay/manage')): ?> active <?php endif; ?>">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.manageBulkPay'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('fastag.index')); ?>" class="nav-link <?php if(Request::is('admin/fastag*')): ?> active <?php endif; ?>">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.fastag'); ?></p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo e(route('accounting.index')); ?>" class="nav-link <?php if(Request::is('admin/accounting*') && !Request::is('admin/accounting/report*') && !Request::is('admin/accounting/transaction-bank') && !Request::is('admin/accounting/transaction-search')): ?> active <?php endif; ?>">
                  <i class="fa fa-inr nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.transactions'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('accounting.index-bank')); ?>" class="nav-link <?php if(Request::is('admin/accounting/transaction-bank')): ?> active <?php endif; ?>">
                  <i class="fa fa-university nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.transactions_bank'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('transaction.search')); ?>" class="nav-link <?php if(Request::is('admin/accounting/transaction-search')): ?> active <?php endif; ?>">
                  <i class="fa fa-search nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.transactions_search'); ?></p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>
          <?php if((Request::is('admin/daily-advance*')) || (Request::is('admin/daily-advance/report*')) || Request::is('admin/other-advance*') || Request::is('admin/other-adjust*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(1,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-paper-plane-o"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.dailyAdvance'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('daily-advance.index')); ?>" class="nav-link <?php if(Request::is('admin/daily-advance*') && !Request::is('admin/daily-advance/report*')): ?> active <?php endif; ?>">
                  <i class="fa fa-hashtag nav-icon"></i>
                  <p>Salary Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('other-advance.index')); ?>" class="nav-link <?php if(Request::is('admin/other-advance*') && !Request::is('admin/other-advance/report*')): ?> active <?php endif; ?>">
                  <i class="fa fa-money nav-icon"></i>
                  <p>Other Advance</p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>
            <?php if((Request::is('admin/driver-logs')) || (Request::is('admin/vehicle-types*')) || (Request::is('admin/vehicles*')) || (Request::is('admin/vehicle_group*')) || (Request::is('admin/vehicle-reviews*')) || (Request::is('admin/view-vehicle-review*')) || (Request::is('admin/vehicle-review*')) || Request::is('admin/vehicle-docs*') || Request::is('admin/vehicles/vehicle-emi*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(1,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-taxi"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.vehicles'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('vehicles.index')); ?>" class="nav-link <?php if(Request::is('admin/vehicles*') && !Request::is('admin/vehicles/report') && !Request::is('admin/vehicles/vehicle-emi*')): ?> active <?php endif; ?>">
                  <i class="fa fa-truck nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.manageVehicles'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('vehicle-emi.index')); ?>" class="nav-link <?php if(Request::is('admin/vehicles/vehicle-emi*')): ?> active <?php endif; ?>">
                  <i class="fa fa-credit-card nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.vehicle_emi'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('vehicle-types.index')); ?>" class="nav-link <?php if(Request::is('admin/vehicle-types*')): ?> active <?php endif; ?>">
                  <i class="fa fa-th-list nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manage_vehicle_types'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/driver-logs')); ?>" class="nav-link <?php if(Request::is('admin/driver-logs*')): ?> active <?php endif; ?>">
                  <i class="fa fa-history nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.driver_logs'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('vehicle_group.index')); ?>" class="nav-link <?php if(Request::is('admin/vehicle_group*')): ?> active <?php endif; ?>">
                  <i class="fa fa-inbox nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manageGroup'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/vehicle-docs')); ?>" class="nav-link <?php if(((Request::is('admin/vehicle-docs*')) || (Request::is('admin/vehicle-docs-report*'))) && !Request::is('admin/vehicle-docs/report') && !Request::is('admin/vehicle-docs/upcoming-renewal-report')): ?> active <?php endif; ?>">
                  <i class="fa fa-id-card-o nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.vehicle_docs'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/vehicle-reviews')); ?>" class="nav-link <?php if((Request::is('admin/vehicle-reviews*')) || (Request::is('admin/view-vehicle-review*')) || (Request::is('admin/vehicle-review*'))): ?> active <?php endif; ?>">
                  <i class="fa fa-briefcase nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.vehicle_inspection'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>

            <?php if((Request::is('admin/income')) || (Request::is('admin/expense')) || (Request::is('admin/transaction')) || (Request::is('admin/income_records')) || (Request::is('admin/expense_records')) ): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          

            <?php if((Request::is('admin/bookings*'))  || (Request::is('admin/bookings_calendar')) || (Request::is('admin/booking-quotation*'))): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>

            <?php endif; ?>
          <?php if(in_array(3,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-address-card"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.bookings'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('bookings.create')); ?>" class="nav-link <?php if(Request::is('admin/bookings/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-address-book nav-icon "></i>
                  <p>
                  <?php echo app('translator')->getFromJson('menu.newbooking'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('bookings.index')); ?>" class="nav-link <?php if((Request::is('admin/bookings*')) && !(Request::is('admin/bookings/create')) && !(Request::is('admin/bookings_calendar'))): ?> active <?php endif; ?>">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p>
                  <?php echo app('translator')->getFromJson('menu.manage_bookings'); ?></p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo e(route('bookings.calendar')); ?>" class="nav-link <?php if(Request::is('admin/bookings_calendar')): ?> active <?php endif; ?>">
                  <i class="fa fa-calendar nav-icon"></i>
                  <p>
                  <?php echo app('translator')->getFromJson('menu.calendar'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>

            <?php if(Request::is('admin/reports*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(4,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-book"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.reports'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if(in_array(3,$modules)): ?>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.statement')); ?>" class="nav-link <?php if(Request::is('admin/reports/statement')): ?> active <?php endif; ?>">
                  <i class="fa fa-newspaper-o nav-icon"></i>
                  <p>Account Statement</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.booking')); ?>" class="nav-link <?php if(Request::is('admin/reports/booking')): ?> active <?php endif; ?>">
                  <i class="fa fa-book nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.bookingReport'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.customerPayment')); ?>" class="nav-link <?php if(Request::is('admin/reports/customer-payment')): ?> active <?php endif; ?>">
                  <i class="fa fa-book nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.customerPayment'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vehicle-docs')); ?>" class="nav-link <?php if(Request::is('admin/reports/vehicle-docs')): ?> active <?php endif; ?>">
                  <i class="fa fa-book  nav-icon"></i>
                  <p>Documents Renew</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.drivers-advance-report')); ?>" class="nav-link <?php if(Request::is('admin/reports/drivers-advance-report')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Driver Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.drivers-report')); ?>" class="nav-link <?php if(Request::is('admin/reports/drivers-report')): ?> active <?php endif; ?>">
                  <i class="fa fa-id-card nav-icon"></i>
                  <p>Driver Payroll</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.fuel')); ?>" class="nav-link <?php if(Request::is('admin/reports/fuel')): ?> active <?php endif; ?>">
                  <i class="fa fa-truck nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.fuelReport'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.fuel-type')); ?>" class="nav-link <?php if(Request::is('admin/reports/fuel-type')): ?> active <?php endif; ?>">
                  <i class="fa fa-exclamation nav-icon"></i>
                  <p>Fuel Type Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.leave')); ?>" class="nav-link <?php if(Request::is('admin/reports/leave')): ?> active <?php endif; ?>">
                  <i class="fa fa-book nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.leave_report'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.other-adjust')); ?>" class="nav-link <?php if(Request::is('admin/reports/other-adjust')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o tag nav-icon"></i>
                  <p>Other Adjust</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.other-advance')); ?>" class="nav-link <?php if(Request::is('admin/reports/other-advance')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o tag nav-icon"></i>
                  <p>Other Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.service-reminder')); ?>" class="nav-link <?php if(Request::is('admin/reports/service-reminder')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o  nav-icon"></i>
                  <p>Reminder Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.salary-advance')); ?>" class="nav-link <?php if(Request::is('admin/reports/salary-advance*')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o tag nav-icon"></i>
                  <p>Salary Advance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.salary-processing')); ?>" class="nav-link <?php if(Request::is('admin/reports/salary-processing')): ?> active <?php endif; ?>">
                  <i class="fa fa-paper-plane nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.salary_processing'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.salary-report')); ?>" class="nav-link <?php if(Request::is('admin/reports/salary-report')): ?> active <?php endif; ?>">
                  <i class="fa fa-align-justify nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.salary_report'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.stock')); ?>" class="nav-link <?php if(Request::is('admin/reports/stock')): ?> active <?php endif; ?>">
                  <i class="fa fa-book nav-icon"></i>
                  <p>Stock Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.transaction')); ?>" class="nav-link <?php if(Request::is('admin/reports/transaction')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.transactions_report'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.upcoming-report')); ?>" class="nav-link <?php if(Request::is('admin/reports/upcoming-renewal')): ?> active <?php endif; ?>">
                  <i class="fa fa-book  nav-icon"></i>
                  <p>Upcoming Renew</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vehicle-advance-report')); ?>" class="nav-link <?php if(Request::is('admin/reports/vehicle-advance-report')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Vehicle Advance Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vehicle-emi')); ?>" class="nav-link <?php if(Request::is('admin/reports/vehicle-emi')): ?> active <?php endif; ?>">
                  <i class="fa fa-book nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.vehicle_emi'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vehicle-fuel-type')); ?>" class="nav-link <?php if(Request::is('admin/reports/vehicle-fuel-type')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Vehicle Fuel Type</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vehicles-overview')); ?>" class="nav-link <?php if(Request::is('admin/reports/vehicles-overview')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o  nav-icon"></i>
                  <p>Vehicle Overview</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vendorPayment')); ?>" class="nav-link <?php if(Request::is('admin/reports/vendor-payment')): ?> active <?php endif; ?>">
                  <i class="fa fa-book nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.vendorPayment'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vendor-report')); ?>" class="nav-link <?php if(Request::is('admin/reports/vendor-report')): ?> active <?php endif; ?>">
                  <i class="fa fa-file-text-o nav-icon"></i>
                  <p>Vendor Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('reports.vendor-work-order')); ?>" class="nav-link <?php if(Request::is('admin/reports/vendor-work-order')): ?> active <?php endif; ?>">
                  <i class="fa fa-flag-o nav-icon"></i>
                  <p>Work-Vendor Report</p>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </li> <?php endif; ?>

            <?php if(Request::is('admin/fuel*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(5,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-filter"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.fuel'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('fuel.create')); ?>" class="nav-link <?php if(Request::is('admin/fuel/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('fleet.add_fuel'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('fuel.index')); ?>" class="nav-link <?php if(Request::is('admin/fuel*') && !Request::is('admin/fuel/create') && !Request::is('admin/fuel/report') && !Request::is('admin/fuel/report-vehicle')): ?> active <?php endif; ?>">
                  <i class="fa fa-history nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manage_fuel'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>

            <?php if(Request::is('admin/vendors*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(6,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-cubes"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.vendors'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('vendors.create')); ?>" class="nav-link <?php if(Request::is('admin/vendors/create') && !Request::is('admin/vendors/report')): ?> active <?php endif; ?>">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('fleet.add_vendor'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('vendors.index')); ?>" class="nav-link <?php if((Request::is('admin/vendors*') && !(Request::is('admin/vendors/create')) && !Request::is('admin/vendors/report'))): ?> active <?php endif; ?>">
                  <i class="fa fa-cube nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manage_vendor'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>


            <?php if((Request::is('admin/parts*') || Request::is('admin/manufacturer*') || Request::is('admin/unit*')) && !Request::is('admin/parts-used*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
           <?php if(in_array(14,$modules)): ?><li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-gears"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.parts'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="<?php echo e(route('parts.index')); ?>" class="nav-link <?php if(Request::is('admin/parts*') && !(Request::is('admin/parts-category*')) && !Request::is('admin/parts/create') && !Request::is('admin/parts-invoice*') && !Request::is('admin/parts-sell*')): ?> active <?php endif; ?>"> 
                  <i class="fa fa-gears nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.manageParts'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('parts-sell.index')); ?>" class="nav-link <?php if(Request::is('admin/parts-sell*')): ?> active <?php endif; ?>">
                  <i class="fa fa-gears nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.partSell'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('parts-invoice.index')); ?>" class="nav-link <?php if(Request::is('admin/parts-invoice*')): ?> active <?php endif; ?>">
                  <i class="fa fa-gears nav-icon"></i>
                  <p>Manage <?php echo app('translator')->getFromJson('menu.parts_inv'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('parts-category.index')); ?>" class="nav-link <?php if(Request::is('admin/parts-category*')): ?> active <?php endif; ?>">
                  <i class="fa fa-list nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.partsCategory'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('unit.index')); ?>" class="nav-link <?php if(Request::is('admin/unit*')): ?> active <?php endif; ?>">
                  <i class="fa fa-cubes nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.unit'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('manufacturer.index')); ?>" class="nav-link <?php if(Request::is('admin/manufacturer*')): ?> active <?php endif; ?>">
                  <i class="fa fa-cube nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manufacturer'); ?></p>
                </a>
              </li>
            </ul>
          </li><?php endif; ?>

            <?php if(Request::is('admin/work_order*') || Request::is('admin/parts-used*') || Request::is('admin/work-order-category*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(7,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-shopping-cart"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.work_orders'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('work_order.create')); ?>" class="nav-link <?php if(Request::is('admin/work_order/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('fleet.add_order'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('work_order.index')); ?>" class="nav-link <?php if((Request::is('admin/work_order*')) && !(Request::is('admin/work_order/create')) && !(Request::is('admin/work_order/logs')) && !Request::is('admin/work_order/report-vendor') || Request::is('admin/parts-used*')): ?> active <?php endif; ?>">
                  <i class="fa fa-inbox nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manage_work_order'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('work-order-category.index')); ?>" class="nav-link <?php if((Request::is('admin/work-order-category*'))): ?> active <?php endif; ?>">
                  <i class="fa fa-level-down nav-icon"></i>
                  <p>Manage <?php echo app('translator')->getFromJson('fleet.order_head'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/work_order/logs')); ?>" class="nav-link <?php if(Request::is('admin/work_order/logs') && !Request::is('admin/work_order/report-vendor')): ?> active <?php endif; ?>">
                  <i class="fa fa-history nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.work_order_logs'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>

            <?php if(Request::is('admin/notes*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(8,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-sticky-note"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.notes'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('notes.index')); ?>" class="nav-link <?php if((Request::is('admin/notes*') && !(Request::is('admin/notes/create')))): ?> active <?php endif; ?>">
                  <i class="fa fa-flag nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('fleet.manage_note'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route("notes.create")); ?>" class="nav-link <?php if(Request::is('admin/notes/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-plus-square nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.create_note'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>

            <?php if((Request::is('admin/service-reminder*')) || (Request::is('admin/service-item*'))): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <?php if(in_array(9,$modules)): ?> <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-clock-o"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.serviceReminders'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('service-reminder.index')); ?>" class="nav-link <?php if(Request::is('admin/service-reminder')): ?> active <?php endif; ?>">
                  <i class="fa fa-arrows-alt nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.manage_reminder'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('service-reminder.create')); ?>" class="nav-link <?php if(Request::is('admin/service-reminder/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-check-square-o nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.add_service_reminder'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('service-item.index')); ?>" class="nav-link <?php if(Request::is('admin/service-item*')): ?> active <?php endif; ?>">
                  <i class="fa fa-warning nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.service_item'); ?></p>
                </a>
              </li>
            </ul>
          </li> <?php endif; ?>
            

          <?php endif; ?> 
          <!-- for user-type O or S -->
          <?php if(Auth::user()->user_type=="S"): ?>
            <?php if(Request::is('admin/team*')): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-users"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.team'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('team.index')); ?>" class="nav-link <?php if((Request::is('admin/team*') && !(Request::is('admin/team/create')))): ?> active <?php endif; ?>">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p> <?php echo app('translator')->getFromJson('fleet.manage_team'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route("team.create")); ?>" class="nav-link <?php if(Request::is('admin/team/create')): ?> active <?php endif; ?>">
                  <i class="fa fa-user-plus nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.addMember'); ?></p>
                </a>
              </li>
            </ul>
          </li>
            <?php if(Request::is('admin/settings*') || Request::is('admin/fare-settings') || Request::is('admin/api-settings') || (Request::is('admin/expensecategories*')) || (Request::is('admin/incomecategories*')) || (Request::is('admin/expensecategories*')) || (Request::is('admin/send-email')) || (Request::is('admin/set-email')) || (Request::is('admin/cancel-reason*')) || (Request::is('admin/frontend-settings*')) || (Request::is('admin/company-services*')) || (Request::is('admin/payment-settings*'))): ?>
            <?php ($class="menu-open"); ?>
            <?php ($active="active"); ?>

            <?php else: ?>
            <?php ($class=""); ?>
            <?php ($active=""); ?>
            <?php endif; ?>
          <li class="nav-item has-treeview <?php echo e($class); ?>">
            <a href="#" class="nav-link <?php echo e($active); ?>">
              <i class="nav-icon fa fa-gear"></i>
              <p>
                <?php echo app('translator')->getFromJson('menu.settings'); ?>
                <i class="right fa fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo e(route('settings.index')); ?>" class="nav-link <?php if(Request::is('admin/settings')): ?> active <?php endif; ?>">
                  <i class="fa fa-gear nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.general_settings'); ?></p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="<?php echo e(route('cancel-reason.index')); ?>" class="nav-link <?php if(Request::is('admin/cancel-reason*')): ?> active <?php endif; ?>">
                  <i class="fa fa-ban nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.cancellation'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/send-email')); ?>" class="nav-link <?php if(Request::is('admin/send-email')): ?> active <?php endif; ?>">
                  <i class="fa fa-envelope nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.email_notification'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/set-email')); ?>" class="nav-link <?php if(Request::is('admin/set-email')): ?> active <?php endif; ?>">
                  <i class="fa fa-envelope-open nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.email_content'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/fare-settings')); ?>" class="nav-link <?php if(Request::is('admin/fare-settings')): ?> active <?php endif; ?>">
                  <i class="fa fa-gear nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.fare_settings'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('expensecategories.index')); ?>" class="nav-link <?php if(Request::is('admin/expensecategories*')): ?> active <?php endif; ?>">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.expenseCategories'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(route('incomecategories.index')); ?>" class="nav-link <?php if(Request::is('admin/incomecategories*')): ?> active <?php endif; ?>">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('menu.incomeCategories'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/frontend-settings')); ?>" class="nav-link <?php if(Request::is('admin/frontend-settings')): ?> active <?php endif; ?>">
                  <i class="fa fa-address-card nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.frontend_settings'); ?></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo e(url('admin/company-services')); ?>" class="nav-link <?php if(Request::is('admin/company-services*')): ?> active <?php endif; ?>">
                  <i class="fa fa-tasks nav-icon"></i>
                  <p><?php echo app('translator')->getFromJson('fleet.companyServices'); ?></p>
                </a>
              </li>
            </ul>
          </li>

          
          <?php endif; ?> <!-- super-admin -->

          <?php if(Hyvikk::api('api') && Hyvikk::api('driver_review') == 1 && in_array(10,$modules)): ?> <li class="nav-item">
            <a href="<?php echo e(url('admin/reviews')); ?>" class="nav-link <?php if(Request::is('admin/reviews')): ?> active <?php endif; ?>">
              <i class="nav-icon fa fa-star"></i>
              <p>
                <?php echo app('translator')->getFromJson('fleet.reviews'); ?>
                <span class="right badge badge-danger"></span>
              </p>
            </a>
          </li> <?php endif; ?>
          <?php if(in_array(Auth::user()->user_type, ['S','O'])): ?>
          
          <?php endif; ?>

          <?php if(in_array(13,$modules)): ?>
           <?php endif; ?>
          <!-- sidebar menus for office-admin and super-admin -->
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?php echo $__env->yieldContent('heading'); ?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php if(!(Request::is('admin'))): ?>
              <li class="breadcrumb-item"><a href="<?php echo e(url('admin/')); ?>"><?php echo app('translator')->getFromJson('fleet.home'); ?></a></li>
              <?php endif; ?>
              <?php echo $__env->yieldContent('breadcrumb'); ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php echo $__env->yieldContent('content'); ?>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong><?php echo app('translator')->getFromJson('fleet.copyright'); ?> &copy; 2020-<?php echo e(date("Y")); ?> <a href="https://hyvikk.com">ScriptX Technologies</a>.</strong>
    <?php echo app('translator')->getFromJson('fleet.all_rights_reserved'); ?>
    <div class="float-right d-none d-sm-inline-block">
      <b><?php echo app('translator')->getFromJson('fleet.version'); ?></b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php echo $__env->yieldContent('script2'); ?>
<!-- jQuery -->
<script src="<?php echo e(asset('assets/plugins/jquery/jquery.min.js')); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo e(asset('assets/js/jquery-ui.min.js')); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- Select2 -->
<script src="<?php echo e(asset('assets/plugins/select2/select2.full.min.js')); ?>"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo e(asset('assets/plugins/iCheck/icheck.min.js')); ?>"></script>
<!-- FastClick -->
<script src="<?php echo e(asset('assets/plugins/fastclick/fastclick.js')); ?>"></script>
<!-- DataTables -->
<script src="<?php echo e(asset('assets/js/cdn/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/plugins/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/dataTables.buttons.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/cdn/buttons.print.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('assets/js/adminlte.js')); ?>"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#data_table tfoot th').each( function () {
    // console.log($('#data_table tfoot th').length);
    if($(this).index() != 0 && $(this).index() != $('#data_table tfoot th').length - 1) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="'+title+'" />' );
    }
  });

  $('#data_table1 tfoot th').each( function () {
    // console.log($(this).index());
    if($(this).index() != 0 && $(this).index() != $('#data_table1 tfoot th').length - 1){
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
  }

  });

  var table1 = $('#data_table1').DataTable({
    // dom: 'Bfrtip',
    buttons: [
          {
        extend: 'print',
        text: '<i class="fa fa-print"></i> <?php echo e(__("fleet.print")); ?>',

        exportOptions: {
           columns: ([1,2,3,4,5,6,7,8,9,10]),
        },
        customize: function ( win ) {
                $(win.document.body)
                    .css( 'font-size', '10pt' )
                    .prepend(
                        '<h3><?php echo e(__("fleet.bookings")); ?></h3>'
                    );
                $(win.document.body).find( 'table' )
                    .addClass( 'table-bordered' );
                // $(win.document.body).find( 'td' ).css( 'font-size', '10pt' );

            }
          }
    ],
    "language": {
             "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
          },
    columnDefs: [ { orderable: false, targets: [0] } ],
    // individual column search
   "initComplete": function() {
            table1.columns().every(function () {
              var that = this;
              $('input', this.footer()).on('keyup change', function () {
                  that.search(this.value).draw();
              });
            });
          }
  });

  var table = $('#data_table').DataTable({
    "language": {
        "url": '<?php echo e(__("fleet.datatable_lang")); ?>',
     },
     columnDefs: [ { orderable: false, targets: [0] } ],
     // individual column search
     "initComplete": function() {
              table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                  // console.log($(this).parent().index());
                    that.search(this.value).draw();
                });
              });
            }
  });

  $('[data-toggle="tooltip"]').tooltip();

});
</script>
<script type="text/javascript" src="<?php echo e(asset('assets/js/pnotify.custom.min.js')); ?>"></script>
<!-- AdminLTE for demo purposes -->

<?php echo $__env->yieldContent('script'); ?>
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/layouts/app.blade.php ENDPATH**/ ?>
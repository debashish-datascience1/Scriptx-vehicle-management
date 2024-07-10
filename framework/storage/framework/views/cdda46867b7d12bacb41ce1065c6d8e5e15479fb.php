<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo e(Hyvikk::get('app_name')); ?></title>
<style type="text/css">
.content-wrapper, .content{
    min-height: 850px !important;
  }
</style>
<link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/png">

<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/bootstrap.min.css')); ?>" />
<link href="<?php echo e(asset('assets/css/cdn/ionicons.min.css')); ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/cdn/font-awesome.min.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/jquery-ui.min.css')); ?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/css/cdn/dataTables.bootstrap.min.css')); ?>">
<link href="<?php echo e(asset('assets/css/select2.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/AdminLTE.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/skins/_all-skins.min.css')); ?>" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<?php echo $__env->yieldContent("extra_css"); ?>
<script>
window.Laravel = <?php echo json_encode([
'csrfToken' => csrf_token(),
]); ?>;


</script>
</head>
<body class="hold-transition skin-black-light sidebar-mini">
  <div class="wrapper">
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>
        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
          <p>
            We could not find the page you were looking for.
            Meanwhile, you may <a href="<?php echo e(url("admin")); ?>">return to dashboard</a>.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
  </div>
</body>
</html><?php /**PATH C:\xampp7.4\htdocs\VehicleMgmt\framework\resources\views/errors/404.blade.php ENDPATH**/ ?>
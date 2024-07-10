<!DOCTYPE html>
<html lang=en  <?php if(Hyvikk::frontend('language') == "ar"): ?> dir="rtl" <?php endif; ?>>
<head>
	<meta charset=utf-8>
	<?php if(Hyvikk::api('api_key')): ?>
	<meta name=mapApi content="<?php echo e(Hyvikk::api('api_key')); ?>">
	<?php endif; ?>
	<meta http-equiv=X-UA-Compatible content="IE=edge">
	<meta name=viewport content="width=device-width,initial-scale=1">
	<meta name=API content=<?php echo e(env('APP_URL')."/frontend"); ?>>
	<title>Fleet Frontend</title>
	<link rel=stylesheet href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
	<link rel=stylesheet href=https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css>
	<meta name="lang" content="<?php echo e(Hyvikk::frontend('language')); ?>" />
	<link href=<?php echo e(asset('/css/app.css')); ?> rel=preload as=style>
	<link href=<?php echo e(asset('/css/chunk-vendors.css')); ?> rel=preload as=style>
	<link href=<?php echo e(asset('/js/app.js')); ?> rel=preload as=script>
	<link href=<?php echo e(asset('/js/chunk-vendors.js')); ?> rel=preload as=script>
	<link href=<?php echo e(asset('/css/chunk-vendors.css')); ?> rel=stylesheet>
	<link href=<?php echo e(asset('/css/app.css')); ?> rel=stylesheet>

</head>

<body <?php if(Hyvikk::frontend('language') == "ar"): ?> dir="rtl" <?php endif; ?>>
	<noscript>
		<strong>We're sorry but fleet-ui-1 doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
	</noscript>
	<div id=app></div>
	<script src=<?php echo e(asset('/js/chunk-vendors.js')); ?>></script>
	<script src=<?php echo e(asset('/js/app.js')); ?>></script>

</body>
</html><?php /**PATH C:\xampp\htdocs\fleet-manager40\framework\resources\views/frontend/index.blade.php ENDPATH**/ ?>
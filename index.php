<?php

require __DIR__ . '/framework/bootstrap/autoload.php';

$app = require_once __DIR__ . '/framework/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);

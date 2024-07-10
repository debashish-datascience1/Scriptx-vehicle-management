<?php

namespace App\Listeners;
use Artisan;

class LoginListener {

	public function __construct() {

	}

	public function handle($event) {

		// Artisan::call('notification:generate');
		Artisan::call('schedule:run');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('push_notification', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->string('user_type')->nullable();
			$table->string('authtoken')->nullable();
			$table->string('contentencoding')->nullable();
			$table->string('endpoint')->nullable();
			$table->string('publickey')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('push_notification');
	}
}

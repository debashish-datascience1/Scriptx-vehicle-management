<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceReminderTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('service_reminder', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('vehicle_id')->nullable();
			$table->integer('service_id')->nullable();
			$table->string('last_date')->nullable();
			$table->integer('last_meter')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('service_reminder');
	}
}

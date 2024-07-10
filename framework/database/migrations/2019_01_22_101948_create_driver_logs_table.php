<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverLogsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('driver_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('vehicle_id');
			$table->integer('driver_id');
			$table->timestamp('date')->nullable();
			$table->nullableTimestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('driver_logs');
	}
}

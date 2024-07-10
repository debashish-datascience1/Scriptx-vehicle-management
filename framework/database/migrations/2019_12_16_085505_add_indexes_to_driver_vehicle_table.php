<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDriverVehicleTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('driver_vehicle', function (Blueprint $table) {
			$table->index(['driver_id', 'vehicle_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('driver_vehicle', function (Blueprint $table) {
			$table->dropIndex(['driver_id', 'vehicle_id']);
		});
	}
}

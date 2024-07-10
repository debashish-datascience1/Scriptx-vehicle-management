<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleTypesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('vehicle_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('vehicletype', 255)->nullable();
			$table->string('displayname', 255)->nullable();
			$table->string('icon', 255)->nullable();
			$table->integer('isenable')->nullable();
			$table->integer('seats')->nullable();
			$table->nullableTimestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('vehicle_types');
	}
}

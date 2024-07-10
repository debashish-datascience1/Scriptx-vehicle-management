<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesMetaTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('vehicles_meta', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('vehicle_id')->unsigned()->index();
			$table->string('type')->default('null');
			$table->string('key')->index();
			$table->longtext('value')->nullable();
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
		Schema::drop('vehicles_meta');
	}
}

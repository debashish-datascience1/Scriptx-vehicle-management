<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceItemsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('service_items', function (Blueprint $table) {
			$table->increments('id');
			$table->text('description')->nullable();
			$table->string('time_interval')->default('off')->nullable();
			$table->string('overdue_time')->nullable();
			$table->string('overdue_unit')->nullable();
			$table->string('meter_interval')->default('off')->nullable();
			$table->string('overdue_meter')->nullable();

			$table->string('show_time')->default('off')->nullable();
			$table->string('duesoon_time')->nullable();
			$table->string('duesoon_unit')->nullable();
			$table->string('show_meter')->default('off')->nullable();
			$table->string('duesoon_meter')->nullable();
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
		Schema::dropIfExists('service_items');
	}
}

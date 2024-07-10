<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('fuel', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('vehicle_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('start_meter')->nullable();
			$table->string('end_meter')->nullable();
			$table->string('reference')->nullable();
			$table->string('province')->nullable();
			$table->text('note')->nullable();
			$table->string('vendor_name')->nullable();
			$table->integer('qty')->nullable();
			$table->string('fuel_from')->nullable();
			$table->string('cost_per_unit')->nullable();
			$table->integer('consumption')->nullable();
			$table->integer('complete')->default(0)->nullable();
			$table->date('date')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('fuel');
	}
}

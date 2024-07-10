<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrdersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('work_orders', function (Blueprint $table) {
			$table->increments('id');
			$table->date('created_on')->nullable();
			$table->date('required_by')->nullable();
			$table->integer('vehicle_id')->nullable();
			$table->integer('vendor_id')->nullable();
			$table->double('price', 8, 2)->nullable();
			$table->string('status')->nullable();
			$table->text('description')->nullable();
			$table->integer('meter')->nullable();
			$table->text('note')->nullable();
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
		Schema::dropIfExists('work_orders');
	}
}

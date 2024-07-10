<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('parts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('category_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->string('status')->nullable();
			$table->integer('availability')->nullable();
			$table->string('title')->nullable();
			$table->string('year')->nullable();
			$table->string('model')->nullable();
			$table->string('image')->nullable();
			$table->string('barcode')->nullable();
			$table->string('number')->nullable();
			$table->string('description')->nullable();
			$table->integer('unit_cost')->nullable();
			$table->integer('vendor_id')->nullable();
			$table->string('manufacturer')->nullable();
			$table->text('note')->nullable();
			$table->integer('stock')->nullable();
			$table->text('udf')->nullable();
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
		Schema::dropIfExists('parts');
	}
}

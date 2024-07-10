<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsUsedTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('parts_used', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('part_id')->nullable();
			$table->integer('work_id')->nullable();
			$table->integer('qty')->nullable();
			$table->double('price')->nullable();
			$table->double('total')->nullable();
			$table->softDeletes();
			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('parts_used');
	}
}

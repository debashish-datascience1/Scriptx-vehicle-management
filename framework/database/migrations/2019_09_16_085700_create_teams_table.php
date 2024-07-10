<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('team', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->text('details')->nullable();
			$table->string('designation')->nullable();
			$table->string('image')->nullable();
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
		Schema::dropIfExists('team');
	}
}

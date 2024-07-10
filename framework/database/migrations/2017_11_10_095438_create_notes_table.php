<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('notes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('vehicle_id')->nullable();
			$table->integer('customer_id')->nullable();
			$table->text('note')->nullable();
			$table->date('submitted_on')->nullable();
			$table->string('status')->nullable();
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
		Schema::dropIfExists('notes');
	}
}

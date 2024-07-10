<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyServicesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('company_services', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title')->nullable();
			$table->string('image')->nullable();
			$table->text('description')->nullable();
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
		Schema::dropIfExists('company_services');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('vendors', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('photo')->nullable();
			$table->string('type')->nullable();
			$table->string('website')->nullable();
			$table->string('custom_type')->nullable();
			$table->text('note')->nullable();
			$table->string('phone')->nullable();
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('city')->nullable();
			$table->string('province')->nullable();
			$table->string('email')->nullable();
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
		Schema::dropIfExists('vendors');
	}
}

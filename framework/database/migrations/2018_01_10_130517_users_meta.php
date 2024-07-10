<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersMeta extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users_meta', function (Blueprint $table) {
			$table->increments('id');

			$table->integer('user_id')->unsigned()->index();
			// $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('type')->default('null');

			$table->string('key')->index();
			$table->text('value')->nullable();
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
		Schema::drop('users_meta');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('reviews', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('booking_id')->nullable();
			$table->integer('driver_id')->nullable();
			$table->float('ratings')->nullable();
			$table->text('review_text')->nullable();
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
		Schema::dropIfExists('reviews');
	}
}

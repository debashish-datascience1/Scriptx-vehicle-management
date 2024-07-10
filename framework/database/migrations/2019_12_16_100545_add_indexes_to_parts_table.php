<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToPartsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('parts', function (Blueprint $table) {
			$table->index(['category_id', 'user_id', 'availability']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('parts', function (Blueprint $table) {
			$table->dropIndex(['category_id', 'user_id', 'availability']);
		});
	}
}

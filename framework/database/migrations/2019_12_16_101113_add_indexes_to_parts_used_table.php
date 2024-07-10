<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToPartsUsedTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('parts_used', function (Blueprint $table) {
			$table->index(['part_id', 'work_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('parts_used', function (Blueprint $table) {
			$table->dropIndex(['part_id', 'work_id']);
		});
	}
}

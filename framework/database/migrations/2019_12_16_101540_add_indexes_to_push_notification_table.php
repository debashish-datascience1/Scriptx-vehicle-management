<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToPushNotificationTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('push_notification', function (Blueprint $table) {
			$table->index(['user_id']);
			$table->index(['user_type']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('push_notification', function (Blueprint $table) {
			$table->dropIndex(['user_id']);
			$table->dropIndex(['user_type']);
		});
	}
}
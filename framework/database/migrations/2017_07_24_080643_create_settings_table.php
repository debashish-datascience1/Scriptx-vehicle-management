<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration {

	public function up() {
		Schema::create('settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('label', 255);
			$table->string('name', 255)->unique();
			$table->longText('value');
			$table->nullableTimestamps();
			$table->softDeletes();

		});
	}

	public function down() {
		Schema::dropIfExists('settings');
	}
}

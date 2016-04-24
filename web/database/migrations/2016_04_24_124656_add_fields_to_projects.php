<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProjects extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projects', function ($table)
		{
			$table->string("photo_path")->nullable()->after("description");
			$table->string("photo_left_offset")->after("photo_path");
			$table->decimal("latitude", 10, 7)->after("photo_left_offset");
			$table->decimal("longitude", 10, 7)->after("latitude");
			$table->dateTime("publishTime")->after("longitude")->nullable();
			$table->string('name', 600)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projects', function ($table)
		{
			$table->dropColumn([
				"photo_path",
				"photo_left_offset",
				"latitude",
				"longitude",
				"publishTime",
			]);
			$table->string('name')->change();
		});
	}
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeWidthNullable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('questions', function (Blueprint $table)
		{
			$table->integer("width")->after("topOffset")->unsigned()->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('questions', function (Blueprint $table)
		{
			$table->integer("width")->after("topOffset")->unsigned()->change;
		});
	}
}

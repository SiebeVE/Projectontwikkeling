<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeFieldsNullableOnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table)
        {
			$table->string('password')->nullable()->change();
			$table->string('address')->nullable()->change();
			$table->string('postal_code')->nullable()->change();
			$table->string('telephone')->nullable()->change();
			$table->string('house_number')->nullable()->change();
			$table->string('city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table)
        {
			$table->string('password')->change();
			$table->string('address')->change();
			$table->string('postal_code')->change();
			$table->string('telephone')->change();
			$table->string('house_number')->change();
			$table->string('city')->change();
		});
    }
}

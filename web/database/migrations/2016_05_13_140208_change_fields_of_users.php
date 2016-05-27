<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsOfUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //$table->dropColumn("address");
            //$table->dropColumn("telephone");
            //$table->dropColumn("house_number");
            //$table->dropColumn("name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("telephone")->after('postal_code');
            $table->integer('house_number')->after('telephone');
            $table->string('address')->after('lastname');
			//$table->string('name')->unique()->before('email');
		});
    }
}

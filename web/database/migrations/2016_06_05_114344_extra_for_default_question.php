<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraForDefaultQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('possible_answers', function(Blueprint $table){
            $table->integer('question_id')->unsigned()->nullable()->change();
            $table->integer("default_question_id")->unsigned()->after("question_id")->nullable();
            $table->foreign('default_question_id')
                ->references('id')
                ->on('default_questions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        Schema::table('questions', function(Blueprint $table){
            $table->string("question")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('possible_answers', function(Blueprint $table){
            $table->dropColumn("default_question_id");
            $table->integer('question_id')->unsigned()->change();
        });

        Schema::table('questions', function(Blueprint $table){
            $table->string("question")->change();
        });
    }
}

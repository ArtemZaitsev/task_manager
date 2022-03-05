<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_log', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->dateTime('date_refresh_plan')->nullable();
            $table->dateTime('date_refresh_fact')->nullable();
            $table->text('trouble');
            $table->text('what_to_do')->nullable();
            $table->timestamps();
        });
        Schema::table('task_log', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('date_refresh_plan', 'date_refresh_fact','trouble', 'what_to_do');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log');
    }
}

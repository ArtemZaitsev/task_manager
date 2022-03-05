<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextFilledToTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('theme')->nullable();
            $table->string('main_task')->nullable();
            $table->string('trouble')->nullable();
            $table->text('what_to_do')->nullable();
            $table->integer('progress');
            $table->date('date_refresh_plan')->nullable();
            $table->date('date_refresh_fact')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task', function (Blueprint $table) {
            //
        });
    }
}

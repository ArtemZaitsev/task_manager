<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('progress')->nullable();

            $table->unsignedBigInteger('system_id')->nullable();
            $table->unsignedBigInteger('subsystem_id')->nullable();
            $table->unsignedBigInteger('detail_id')->nullable();
            $table->unsignedBigInteger('physical_object_id')->nullable();

        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('system_id')->references('id')->on('systems');
            $table->foreign('subsystem_id')->references('id')->on('subsystems');
            $table->foreign('detail_id')->references('id')->on('details');
            $table->foreign('physical_object_id')->references('id')->on('physical_objects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

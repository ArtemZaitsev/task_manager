<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskCoperformer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('task_coperformer', function (Blueprint $table) {
            $table->integer('task_id');
            $table->unsignedBigInteger('user_id');

        });
        Schema::table('task_coperformer', function (Blueprint $table) {

            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique([
                'task_id',
                'user_id',
            ], 'task_user_unique');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrevIdChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_prev_id_foreign');
            $table->dropColumn('prev_id');
        });

        Schema::create('tasks_prev', function (Blueprint $table) {
            $table->integer('task_id');
            $table->integer('task_prev_id');
        });

        Schema::table('tasks_prev', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('task_prev_id')->references('id')->on('tasks');

            $table->unique([
                'task_id',
                'task_prev_id',
            ], 'tasks_prev_unique');
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

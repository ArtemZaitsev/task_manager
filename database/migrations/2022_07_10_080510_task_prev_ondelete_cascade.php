<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskPrevOndeleteCascade extends Migration
{

    public function up()
    {
        Schema::table('tasks_prev', function (Blueprint $table) {
            $table->dropForeign('tasks_prev_task_id_foreign');
            $table->dropForeign('tasks_prev_task_prev_id_foreign');

            $table->foreign('task_id')
                ->references('id')->on('tasks')
                ->onDelete('CASCADE');

            $table->foreign('task_prev_id')
                ->references('id')->on('tasks')
                ->onDelete('CASCADE');
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

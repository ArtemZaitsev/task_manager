<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignIdForTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_family', function (Blueprint $table) {
            $table->dropForeign('task_family_task_id_foreign');
            $table->foreign('task_id')->references('id')->on('tasks')
                ->onDelete('cascade');

        });

        Schema::table('task_product', function (Blueprint $table) {
            $table->dropForeign('task_product_task_id_foreign');
            $table->foreign('task_id')->references('id')->on('tasks')
                ->onDelete('cascade');

        });

        Schema::table('task_project', function (Blueprint $table) {
            $table->dropForeign('task_project_task_id_foreign');
            $table->foreign('task_id')->references('id')->on('tasks')
                ->onDelete('cascade');

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

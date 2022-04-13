<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_product_id_foreign');
            $table->dropColumn('product_id');
        });

        Schema::create('task_project', function (Blueprint $table) {
            $table->integer('task_id');
            $table->unsignedBigInteger('project_id');
        });
        Schema::create('task_family', function (Blueprint $table) {
            $table->integer('task_id');
            $table->unsignedBigInteger('family_id');
        });
        Schema::create('task_product', function (Blueprint $table) {
            $table->integer('task_id');
            $table->unsignedBigInteger('product_id');
        });


        Schema::table('task_project', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unique([
                'task_id',
                'project_id',
            ], 'task_project_unique');
        });
        Schema::table('task_family', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('family_id')->references('id')->on('families');
            $table->unique([
                'task_id',
                'family_id',
            ], 'task_family_unique');
        });
        Schema::table('task_product', function (Blueprint $table) {
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unique([
                'task_id',
                'product_id',
            ], 'task_product_unique');
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

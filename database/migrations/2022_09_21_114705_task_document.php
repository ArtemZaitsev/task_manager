<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('number');
            $table->date('date');
            $table->string('title')->nullable();
            $table->string('file_path');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('task_document_id')->nullable();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('task_document_id')->references('id')->on('task_documents');
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

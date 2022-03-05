<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('title');
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('planer_id')->nullable();
            $table->unsignedBigInteger('project_id');
        });
        Schema::table('families', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users');
            $table->foreign('planer_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('families');
    }
}

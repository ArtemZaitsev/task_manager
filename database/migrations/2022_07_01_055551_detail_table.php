<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->unique();
            $table->string('number')->nullable()->unique();
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('subsystem_id');

        });

        Schema::table('details', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users');
            $table->foreign('subsystem_id')->references('id')->on('subsystems');
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

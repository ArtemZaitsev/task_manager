<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SzAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sz', function (Blueprint $table) {
            $table->unsignedBigInteger('initiator_id')->nullable();
            $table->unsignedBigInteger('target_user_id')->nullable();
        });

        Schema::table('sz', function (Blueprint $table) {
            $table->foreign('initiator_id')->references('id')->on('users');
            $table->foreign('target_user_id')->references('id')->on('users');

        });

        Schema::create('sz_objects', function (Blueprint $table) {
            $table->unsignedBigInteger('sz_id');
            $table->unsignedBigInteger('object_id');
        });

        Schema::table('sz_objects', function (Blueprint $table) {
            $table->foreign('sz_id')->references('id')->on('sz');
            $table->foreign('object_id')->references('id')->on('physical_objects');
            $table->unique(['sz_id','object_id']);
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

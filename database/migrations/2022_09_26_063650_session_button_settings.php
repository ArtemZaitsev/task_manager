<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SessionButtonSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grid_settings', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('grid');
            $table->json('settings_data');
        });

        Schema::table('grid_settings',function (Blueprint $table){
           // $table->unique(['user_id','settings_data']);
            $table->foreign('user_id')->references('id')->on('users');
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

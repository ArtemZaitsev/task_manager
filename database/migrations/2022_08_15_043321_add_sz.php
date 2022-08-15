<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sz', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('number');
            $table->date('date');
            $table->string('title')->nullable();
            $table->string('file_path');
        });

        Schema::table('components', function (Blueprint $table) {
            $table->unsignedBigInteger('sz_id')->nullable();
        });
        Schema::table('components', function (Blueprint $table) {
            $table->foreign('sz_id')->references('id')->on('sz');
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

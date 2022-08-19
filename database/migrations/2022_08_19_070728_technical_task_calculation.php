<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TechnicalTaskCalculation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_task_calculations', function (Blueprint $table){
            $table->id();
            $table->timestamps();

            $table->string('number');
            $table->date('date');
            $table->string('title')->nullable();
            $table->string('file_path');
        });
        Schema::table('components', function (Blueprint $table) {
            $table->unsignedBigInteger('technical_task_calculation_id')->nullable();
        });
        Schema::table('components', function (Blueprint $table) {
            $table->foreign('technical_task_calculation_id')->references('id')->on('technical_task_calculations');
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

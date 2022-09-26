<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DrawingFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawing_files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('number');
            $table->date('date');
            $table->string('title')->nullable();
            $table->string('file_path')->nullable();
        });

        Schema::table('components', function (Blueprint $table) {
            $table->unsignedBigInteger('drawing_files_id')->nullable();
        });
        Schema::table('components', function (Blueprint $table) {
            $table->foreign('drawing_files_id')->references('id')->on('drawing_files');
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

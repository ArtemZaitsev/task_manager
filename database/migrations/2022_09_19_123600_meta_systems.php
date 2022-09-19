<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MetaSystems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metasystems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title')->unique();
        });

        Schema::table('systems', function (Blueprint $table){
            $table->unsignedBigInteger('metasystem_id')->nullable();
        });

        Schema::table('systems', function (Blueprint $table) {
            $table->foreign('metasystem_id')->references('id')->on('metasystems');
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

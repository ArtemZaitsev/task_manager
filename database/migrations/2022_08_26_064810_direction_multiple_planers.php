<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DirectionMultiplePlaners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direction_planers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('planer_id');
            $table->unsignedBigInteger('direction_id');
        });
        Schema::table('direction_planers', function (Blueprint $table) {
            $table->foreign('planer_id')->references('id')->on('users');
            $table->foreign('direction_id')->references('id')->on('directions');
        });

        DB::statement("INSERT INTO direction_planers(planer_id, direction_id) ".
            "SELECT d.planer_id, d.id FROM directions d WHERE d.planer_id IS NOT NULL");

        Schema::table('directions', function (Blueprint $table) {
            $table->dropForeign('directions_planer_id_foreign');
            $table->dropColumn('planer_id');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FamilyHeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_heads', function (Blueprint $table) {
            $table->unsignedBigInteger('family_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table('family_heads', function (Blueprint $table) {

            $table->foreign('family_id')->references('id')->on('families');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique([
                'family_id',
                'user_id',
            ], 'family_head_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down()
    {
        //
    }
}

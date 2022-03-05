<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectionGroupSubgroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('title');
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('planer_id')->nullable();
        });
        Schema::table('directions', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users');
            $table->foreign('planer_id')->references('id')->on('users');
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('title');
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('direction_id');
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users');
            $table->foreign('direction_id')->references('id')->on('directions');
        });

        Schema::create('subgroups', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('title');
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('group_id');

        });
        Schema::table('subgroups', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users');
            $table->foreign('group_id')->references('id')->on('groups');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direction_group_subgroup_tables');
    }
}

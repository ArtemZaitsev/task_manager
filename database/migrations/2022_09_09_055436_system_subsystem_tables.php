<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemSubsystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function(Blueprint $table){
           $table->dropForeign('tasks_system_id_foreign');
           $table->dropForeign('tasks_subsystem_id_foreign');
           $table->dropForeign('tasks_detail_id_foreign');
           $table->dropColumn('system_id');
           $table->dropColumn('subsystem_id');
           $table->dropColumn('detail_id');
        });

        Schema::drop('details');
        Schema::drop('subsystems');
        Schema::drop('systems');

        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title')->unique();
        });

        Schema::create('subsystems', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title')->unique();
            $table->unsignedBigInteger('system_id');
        });

        Schema::table('subsystems', function (Blueprint $table) {
            $table->foreign('system_id')->references('id')->on('systems');
        });

        Schema::table('components', function (Blueprint $table) {
            $table->unsignedBigInteger('system_id')->nullable();
            $table->unsignedBigInteger('subsystem_id')->nullable();
        });
        Schema::table('components', function (Blueprint $table) {
            $table->foreign('system_id')->references('id')->on('systems');
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

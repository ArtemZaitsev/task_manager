<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SzNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sz', function (Blueprint $table) {
            $table->string('file_path')->nullable()->change();
        });
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('file_path')->nullable()->change();
        });
        Schema::table('technical_task_calculations', function (Blueprint $table) {
            $table->string('file_path')->nullable()->change();
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

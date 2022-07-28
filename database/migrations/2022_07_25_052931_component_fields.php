<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComponentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->integer('3d_status')->nullable();
            $table->date('3d_date_plan')->nullable();
            $table->integer('dd_status')->nullable();
            $table->date('dd_date_plan')->nullable();
            $table->string('drawing_files')->nullable();
            $table->date('drawing_date_plan')->nullable();
            $table->integer('calc_status')->nullable();
            $table->date('calc_date_plan')->nullable();
            $table->string('tz_files')->nullable();
            $table->date('tz_date_plan')->nullable();
            $table->integer('constructor_priority')->nullable();
            $table->string('constructor_comment')->nullable();
            $table->unsignedBigInteger('manufactor_id')->nullable();
            $table->integer('manufactor_status')->nullable();
            $table->date('manufactor_date_plan')->nullable();
            $table->string('manufactor_sz_files')->nullable();
            $table->date('manufactor_sz_date')->nullable();
            $table->integer('manufactor_sz_quantity')->nullable();
            $table->integer('manufactor_priority')->nullable();
            $table->string('manufactor_comment')->nullable();

            $table->unsignedBigInteger('purchaser_id')->nullable();
            $table->integer('purchase_status')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('purchase_request_files')->nullable();
            $table->date('purchase_request_date')->nullable();
            $table->integer('purchase_request_quantity')->nullable();
            $table->integer('purchase_request_priority')->nullable();
            $table->string('purchase_comment')->nullable();
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

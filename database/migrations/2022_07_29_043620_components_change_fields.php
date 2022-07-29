<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComponentsChangeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropColumn(['drawing_date_plan', 'tz_date_plan', 'purchase_date']);
            $table->date('drawing_date')->nullable();
            $table->date('tz_date')->nullable();
            $table->date('purchase_date_plan')->nullable();
            $table->integer('quantity_in_object')->nullable();
            $table->unsignedBigInteger('physical_object_id')->nullable();
        });

        Schema::drop('components_physical_objects');

        Schema::table('components', function (Blueprint $table) {
            $table->foreign('physical_object_id')->on('physical_objects')->references('id');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PurchaseOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table){
            $table->id();
            $table->timestamps();

            $table->string('number');
            $table->date('date');
            $table->string('title')->nullable();
            $table->string('file_path');
        });
        Schema::table('components', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_id')->nullable();
        });
        Schema::table('components', function (Blueprint $table) {
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
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

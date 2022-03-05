<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('title');
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('planer_id')->nullable();
            $table->unsignedBigInteger('family_id');

        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('head_id')->references('id')->on('users');
            $table->foreign('planer_id')->references('id')->on('users');
            $table->foreign('family_id')->references('id')->on('families');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

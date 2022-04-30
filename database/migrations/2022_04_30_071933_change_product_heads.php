<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeProductHeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_heads', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table('product_heads', function (Blueprint $table) {

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique([
                'product_id',
                'user_id',
            ], 'product_head_unique');
        });

        DB::insert('INSERT INTO product_heads (product_id,user_id) SELECT id,head_id FROM products WHERE head_id is not null');
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_head_id_foreign');
            $table->dropColumn('head_id');
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

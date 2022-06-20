<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at', 0)->nullable();

            $table->unsignedBigInteger('user_id');
            $table->integer('event_type');

            $table->string('table_name');
            $table->unsignedBigInteger('entity_id');

            $table->json('meta_inf')->nullable();

        });

        Schema::table('audit', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
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

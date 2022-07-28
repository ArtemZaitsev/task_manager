<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('components', function (Blueprint $table){
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->string('identifier')->nullable();
            $table->integer('entry_level')->nullable();
            $table->tinyInteger('source_type')->nullable();
            $table->tinyInteger('version')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->unsignedBigInteger('constructor_id')->nullable();
        });

        Schema::table('components', function (Blueprint $table){
           $table->foreign('constructor_id')->references('id')->on('users');
        });

        Schema::create('relative_components', function (Blueprint $table){
            $table->unsignedBigInteger('component_id');
            $table->unsignedBigInteger('relative_component_id');
        });

        Schema::table('relative_components', function (Blueprint $table){
            $table->foreign('component_id')->references('id')->on('components');
            $table->foreign('relative_component_id')->references('id')->on('components');
        });


        Schema::create('components_physical_objects', function (Blueprint $table){
            $table->unsignedBigInteger('component_id');
            $table->unsignedBigInteger('physical_object_id');
        });

        Schema::table('components_physical_objects', function (Blueprint $table){
            $table->foreign('component_id')->references('id')->on('components');
            $table->foreign('physical_object_id')->references('id')->on('physical_objects');
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

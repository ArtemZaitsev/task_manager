<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilesDel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_documents', function (Blueprint $table){
            $table->dropColumn('file_path');
        });
        Schema::table('sz', function (Blueprint $table){
            $table->dropColumn('file_path');
        });
        Schema::table('purchase_orders', function (Blueprint $table){
            $table->dropColumn('file_path');
        });
        Schema::table('technical_task_calculations', function (Blueprint $table){
            $table->dropColumn('file_path');
        });
        Schema::table('drawing_files', function (Blueprint $table){
            $table->dropColumn('file_path');
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

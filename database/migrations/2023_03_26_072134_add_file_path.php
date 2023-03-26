<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePath extends Migration
{
    public function up()
    {
        Schema::table('task_documents', function (Blueprint $table) {
            $table->string('file_path')->nullable();
        });
    }

    public function down()
    {

    }
}

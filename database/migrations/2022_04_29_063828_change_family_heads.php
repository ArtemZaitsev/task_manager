<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeFamilyHeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert('INSERT INTO family_heads (family_id,user_id) SELECT id,head_id FROM families WHERE head_id is not null');
        Schema::table('families', function (Blueprint $table) {
            $table->dropForeign('families_head_id_foreign');
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

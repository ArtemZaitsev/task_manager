<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignIdForUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_direction_id_foreign');
            $table->dropForeign('users_group_id_foreign');
            $table->dropForeign('users_subgroup_id_foreign');
            $table->foreign('direction_id')->references('id')->on('directions')
                ->onDelete('set null');
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('set null');
            $table->foreign('subgroup_id')->references('id')->on('subgroups')
                ->onDelete('set null');

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

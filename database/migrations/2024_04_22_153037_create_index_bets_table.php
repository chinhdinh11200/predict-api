<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->index(['user_id', 'bet_type', 'is_demo']);
            $table->index(['user_id', 'type', 'is_demo']);
            $table->index(['user_id', 'result', 'is_demo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->dropIndex('bets_user_id_bet_type_is_demo_index');
            $table->dropIndex('bets_user_id_type_is_demo_index');
            $table->dropIndex('bets_user_id_result_is_demo_index');
        });
    }
};

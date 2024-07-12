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
        if (!Schema::hasColumn('history_command_bets', 'type_target')) {
            Schema::table('history_command_bets', function (Blueprint $table) {
                $table->tinyInteger('type_target')->nullable()->comment('Type command is BUY or SELL.');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('history_command_bets', 'type_target')) {
            Schema::table('history_command_bets', function (Blueprint $table) {
                $table->dropColumn('type_target');
            });
        }
    }
};

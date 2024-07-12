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
        if (!Schema::hasColumn('ticket_history', 'lucky_wheel_id')) {
            Schema::table('ticket_history', function (Blueprint $table) {
                $table->unsignedBigInteger('lucky_wheel_id')->nullable();
                $table->foreign('lucky_wheel_id')->references('id')->on('lucky_wheels')->onDelete('cascade');
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
        if (Schema::hasColumn('ticket_history', 'lucky_wheel_id')) {
            Schema::table('ticket_history', function (Blueprint $table) {
                $table->dropForeign('ticket_history_lucky_wheel_id_foreign');
                $table->dropColumn('lucky_wheel_id');
            });
        }
    }
};

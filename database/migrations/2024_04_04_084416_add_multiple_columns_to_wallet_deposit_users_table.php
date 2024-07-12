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
        Schema::table('wallet_deposit_users', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_transfer_fee')->after('public_key')->default(0);
            $table->decimal('balance', 15, 8)->after('public_key')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_deposit_users', function (Blueprint $table) {
            $table->dropColumn(['is_transfer_fee', 'balance']);
        });
    }
};

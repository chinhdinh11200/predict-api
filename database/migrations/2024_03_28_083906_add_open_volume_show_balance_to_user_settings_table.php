<?php

use App\Models\UserSetting;
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
        if (!Schema::hasColumn('user_settings', 'open_volume')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('open_volume')->default(UserSetting::OPEN_VOLUME);
            });
        }
        if (!Schema::hasColumn('user_settings', 'show_balance')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->boolean('show_balance')->default(UserSetting::SHOW_BALANCE);
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
        if (Schema::hasColumn('user_settings', 'open_volume')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('open_volume');
            });
        }
        if (Schema::hasColumn('user_settings', 'show_balance')) {
            Schema::table('user_settings', function (Blueprint $table) {
                $table->dropColumn('show_balance');
            });
        }
    }
};

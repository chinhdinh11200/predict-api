<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserExistSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::query()->doesntHave('setting')->get();
        
        foreach ($users as $key => $user) {
            UserSetting::create([
                'user_id' => $user->id,
                'is_revert' => UserSetting::NONE_REVERT,
                'is_marketing' => UserSetting::NONE_MARKETING,
                'is_lock_transaction' => UserSetting::NONE_LOCK_TRANSACTION,
            ]);
        }

        $userSettings = UserSetting::query()->get();
        foreach ($userSettings as $key => $userSetting) {
            $userSetting->update([
                'open_volume' => UserSetting::OPEN_VOLUME,
                'show_balance' => UserSetting::SHOW_BALANCE, 
            ]);
        }
    }
}

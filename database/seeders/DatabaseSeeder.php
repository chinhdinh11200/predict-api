<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            AdminSeeder::class,
            SettingSeeder::class,
            PoolSeeder::class,
            LuckyWheelSeeder::class,
            CoinSeeder::class,
            ChartSeeder::class,
            IndicatorSeeder::class,
            AgencyCommissionSeeder::class,
            LevelConditionSeeder::class,
            SystemWalletSeeder::class,
        ]);
    }
}

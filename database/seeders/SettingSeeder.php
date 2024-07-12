<?php

namespace Database\Seeders;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'key' => 'trade_min',
                'value' => 1,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'key' => 'withdraw_fee',
                'value' => 1,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'key' => 'start_golden_hour',
                'value' => Carbon::now()->toDateTimeString(),
                'type' => 'datetime',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'key' => 'end_golden_hour',
                'value' => Carbon::now()->toDateTimeString(),
                'type' => 'datetime',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'key' => 'ticket_price_golden_hour',
                'value' => 2,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'key' => 'ticket_price',
                'value' => 3,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'key' => 'profit_bet',
                'value' => 95,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'key' => 'basic_agency_fee',
                'value' => 100,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'key' => 'vip_agency_fee',
                'value' => 3000,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'key' => 'rate_to_system_wallet',
                'value' => 50,
                'type' => 'number',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Schema::disableForeignKeyConstraints();
        Setting::query()->truncate();
        Schema::enableForeignKeyConstraints();

        Setting::query()->insert($data);
    }
}

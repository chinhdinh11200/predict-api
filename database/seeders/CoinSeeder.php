<?php

namespace Database\Seeders;

use App\Models\Coin;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = Carbon::now();
        $updatedAt = Carbon::now();
        $data = [
            [
                'name' => 'BTC/USD',
                'image' => null,
                'status' => Coin::STATUS_ACTIVE,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'BTC/USDT',
                'image' => null,
                'status' => Coin::STATUS_INACTIVE,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
            [
                'name' => 'BTC/EUR',
                'image' => null,
                'status' => Coin::STATUS_INACTIVE,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ],
        ];

        Schema::disableForeignKeyConstraints();
        Coin::query()->truncate();
        Schema::enableForeignKeyConstraints();

        Coin::query()->insert($data);
    }
}

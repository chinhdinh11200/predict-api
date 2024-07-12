<?php

namespace Database\Seeders;

use App\Models\Indicators;
use App\Models\TraderSentiments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class IndicatorSeeder extends Seeder
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
                'key' => 'oscillators',
                'buy' => 4,
                'sell' => 0,
                'neutral' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'key' => 'summary',
                'buy' => 12,
                'sell' => 4,
                'neutral' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'key' => 'moving_averages',
                'buy' => 8,
                'sell' => 4,
                'neutral' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        Schema::disableForeignKeyConstraints();
        Indicators::query()->truncate();
        TraderSentiments::query()->truncate();
        Schema::enableForeignKeyConstraints();

        TraderSentiments::query()->create([
            'sell' => 49,
            'buy' => 51
        ]);
        Indicators::query()->insert($data);
    }
}

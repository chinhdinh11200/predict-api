<?php

namespace Database\Seeders;

use App\Models\Chart;
use App\Models\Coin;
use App\Models\LastResult;
use App\Services\Common\SessionServiceCommon;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ChartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = SessionServiceCommon::getInstance()->getSession(50);
        $charts = [];
        $lastResults = [];
        $coin = Coin::query()->where('status', Coin::STATUS_ACTIVE)->first();
        foreach ($data as $key => $item) {
            $createdAt = Carbon::now();
            $updatedAt = Carbon::now();
            $key++;
            $openTime = $item[0];
            $open = $item[1];
            $high = $item[2];
            $low = $item[3];
            $close = $item[4];
            $volume = $item[5];
            $closeTime = $item[6];
            $assetVolume = $item[7];

            $lastResults[] = [
                'id' => $key,
                'start_time' => $openTime,
                'end_time' => $closeTime,
                'result' => $open < $close ? LastResult::UP : LastResult::DOWN,
                'is_bet_session' => $key % 2 == 0,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            $charts[] = [
                'id' => $key,
                'session_id' => $key,
                'coin_id' => $coin?->id,
                'low_price' => $low,
                'high_price' => $high,
                'open_price' => $open,
                'close_price' => $close,
                'start_time' => $openTime,
                'end_time' => $closeTime,
                'volume' => $volume,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }//end foreach

        Schema::disableForeignKeyConstraints();
        Chart::query()->truncate();
        LastResult::query()->truncate();
        Schema::enableForeignKeyConstraints();

        Chart::query()->insert($charts);
        LastResult::query()->insert($lastResults);
    }
}

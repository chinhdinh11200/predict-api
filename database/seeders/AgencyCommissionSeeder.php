<?php

namespace Database\Seeders;

use App\Models\NormalAgencyCondition;
use App\Models\AgencyCondition;
use App\Models\VipAgencyCondition;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AgencyCommissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $normalAmount = [
            [50],
            [50, 25],
            [50, 25, 12.5],
            [50, 25, 12.5, 6.25],
            [50, 25, 12.5, 6.25, 3.125],
            [50, 25, 12.5, 6.25, 3.125, 1.562],
            [50, 25, 12.5, 6.25, 3.125, 1.562, 0.781],
        ];

        $vipAmount = [
            [1000],
            [1000, 500],
            [1000, 500, 250],
            [1000, 500, 250, 125],
            [1000, 500, 250, 125, 62.5],
            [1000, 500, 250, 125, 62.5, 31.25],
            [1000, 500, 250, 125, 62.5, 31.25, 15.62],
        ];

        $downAmount = [
            [1],
            [1, 0.5],
            [1, 0.5, 0.25],
            [1, 0.5, 0.25, 0.125],
            [1, 0.5, 0.25, 0.125, 0.0625],
            [1, 0.5, 0.25, 0.125, 0.0625, 0.03125],
            [1, 0.5, 0.25, 0.125, 0.0625, 0.03125, 0.015625],
        ];

        $downArr = $this->getArrData($downAmount, false);
        $vipArr = $this->getArrData($vipAmount);
        $normalArr = $this->getArrData($normalAmount);

        Schema::disableForeignKeyConstraints();
        NormalAgencyCondition::query()->truncate();
        VipAgencyCondition::query()->truncate();
        AgencyCondition::query()->truncate();
        Schema::enableForeignKeyConstraints();

        NormalAgencyCondition::query()->insert($normalArr);
        VipAgencyCondition::query()->insert($vipArr);
        AgencyCondition::query()->insert($downArr);
    }

    /**
     * Get array data
     *
     * @param $arr
     * @param bool $down
     * @return array
     */
    public function getArrData($arr, bool $down = true): array
    {
        $levels = [1, 2, 3, 4, 5, 6, 7];
        $generations = [1, 2, 3, 4, 5, 6, 7];
        $newArr = [];
        foreach ($arr as $key => $value) {
            $level = $levels[$key];
            foreach ($value as $key2 => $item) {
                if (array_key_exists($key2, $levels) && array_key_exists($key2, $generations)) {
                    $newItem = [
                        'level' => $level,
                        'generation' => $generations[$key2],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];

                    if (!$down) {
                        $newItem['percent'] = $item;
                    } else {
                        $newItem['amount'] = $item;
                    }//end if

                    $newArr[] = $newItem;
                }
            }
        }

        return $newArr;
    }
}

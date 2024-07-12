<?php

namespace Database\Seeders;

use App\Models\NormalAgencyCondition;
use App\Models\AgencyCondition;
use App\Models\LevelCondition;
use App\Models\VipAgencyCondition;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LevelConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [1, 2, 3, 4, 5, 6, 7];
        $f1Condition = [0, 1, 2, 3, 4, 5, 6];
        $volume = [300, 500, 1000, 1500, 2000, 4000, 8000];
        $data = [];
        foreach ($levels as $key => $level) {
            $data[] = [
                'level' => $level,
                'condition_f1' => $f1Condition[$key],
                'volume_week' => $volume[$key],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        Schema::disableForeignKeyConstraints();
        LevelCondition::query()->truncate();
        Schema::enableForeignKeyConstraints();

        LevelCondition::query()->insert($data);
    }
}

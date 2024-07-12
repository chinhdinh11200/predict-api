<?php

namespace Database\Seeders;

use App\Models\LuckyWheel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LuckyWheelSeeder extends Seeder
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
                'name_vi' => 'Ô mất lượt',
                'name_en' => 'Miss a Turn',
                'image_url' => 'images/miss.png',
                'slice_quantity' => 3,
                'prize_quantity' => 450,
                'winning_probability' => 45,
                'reward' => 0,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name_vi' => 'Ô quay lại',
                'name_en' => 'Go Back 4 Spaces',
                'image_url' => 'images/spin_again.png',
                'slice_quantity' => 4,
                'prize_quantity' => 120,
                'winning_probability' => 12,
                'reward' => 0,
                'spin_again' => LuckyWheel::SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name_vi' => 'Thưởng 1$',
                'name_en' => '$1 Prize',
                'image_url' => 'images/1.png',
                'slice_quantity' => 6,
                'prize_quantity' => 150,
                'winning_probability' => 15,
                'reward' => 1,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name_vi' => 'Thưởng 5$',
                'name_en' => '$5 Prize',
                'image_url' => 'images/5.png',
                'slice_quantity' => 6,
                'prize_quantity' => 120,
                'winning_probability' => 12,
                'reward' => 5,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name_vi' => 'Thưởng 10$',
                'name_en' => '$10 Prize',
                'image_url' => 'images/10.png',
                'slice_quantity' => 3,
                'prize_quantity' => 80,
                'winning_probability' => 8,
                'reward' => 10,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name_vi' => 'Thưởng 50$',
                'name_en' => '$50 Prize',
                'image_url' => 'images/50.png',
                'slice_quantity' => 3,
                'prize_quantity' => 50,
                'winning_probability' => 5,
                'reward' => 50,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name_vi' => 'Thưởng 100$',
                'name_en' => '$100 Prize',
                'image_url' => 'images/100.png',
                'slice_quantity' => 2,
                'prize_quantity' => 20,
                'winning_probability' => 2,
                'reward' => 100,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'name_vi' => 'Thưởng 500$',
                'name_en' => '$500 Prize',
                'image_url' => 'images/500.png',
                'slice_quantity' => 1,
                'prize_quantity' => 8,
                'winning_probability' => 0.8,
                'reward' => 500,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'name_vi' => 'Thưởng 5,000$',
                'name_en' => '$5,000 Prize',
                'image_url' => 'images/5000.png',
                'slice_quantity' => 1,
                'prize_quantity' => 1,
                'winning_probability' => 0.1,
                'reward' => 5000,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'name_vi' => 'Thưởng 10,000$',
                'name_en' => '$10,000 Prize',
                'image_url' => 'images/10000.png',
                'slice_quantity' => 1,
                'prize_quantity' => 1,
                'winning_probability' => 0.1,
                'reward' => 10000,
                'spin_again' => LuckyWheel::NO_SPIN_AGAIN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Schema::disableForeignKeyConstraints();
        LuckyWheel::query()->truncate();
        Schema::enableForeignKeyConstraints();

        LuckyWheel::query()->insert($data);
    }
}

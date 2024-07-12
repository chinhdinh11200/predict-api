<?php

namespace Database\Seeders;

use App\Models\SystemWallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_wallets')->truncate();
        SystemWallet::query()->create(['value' => 0]);
    }
}

<?php

namespace App\Console\Commands;

use App\Console\Kernel;
use App\Jobs\CollectTokenJob;
use App\Models\WalletDepositUser;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class UpdateColdWalletCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Kernel::SCHEDULE_COLLECT_WALLET;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CMD] Collect token transactions.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $minimumWithdrawAmount = config('user.minimum_withdraw_amount');
        WalletDepositUser::query()
            ->where('balance', '>', $minimumWithdrawAmount)
            ->chunk(50, function ($walletDepositUsers) {
                foreach ($walletDepositUsers as $walletDepositUser) {
                    try {
                        dispatch(new CollectTokenJob($walletDepositUser->id))->onQueue(config('queue.job_name.collect_token_user'));
                    } catch (Exception $exception) {
                        Log::error("Error wallet ID: {$walletDepositUser->id}, Err: {$exception->getMessage()}");
                    }
                }
            });

        return Command::SUCCESS;
    }
}

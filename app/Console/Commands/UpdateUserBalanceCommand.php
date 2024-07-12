<?php

namespace App\Console\Commands;

use App\Console\Kernel;
use App\Models\Bet;
use App\Models\HistoryCommission;
use App\Models\TicketHistory;
use App\Models\TransactionDetail;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateUserBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Kernel::CMD_UPDATE_USER_BALANCE;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Cmd] Update User Balance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->option('user_id');
        $user = User::query()->find($userId);

        if (!$userId && !$user) {
            return Command::FAILURE;
        }

        $depositAmount = TransactionDetail::query()
            ->where('user_id', $userId)
            ->where('type', TransactionDetail::TRANSACTION_TYPE_DEPOSIT)
            ->where('status', TransactionDetail::TRANSACTION_STATUS_COMPLETED)
            ->sum('amount');
        $withdrawAmount = TransactionDetail::query()
            ->where('user_id', $userId)
            ->where('type', TransactionDetail::TRANSACTION_TYPE_WITHDRAW)
            ->where('status', TransactionDetail::TRANSACTION_STATUS_COMPLETED)
            ->sum(DB::raw('amount + fee'));
        $betWinAmount = Bet::query()
            ->where('user_id', $userId)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->where('result', Bet::WIN)
            ->sum(DB::raw('reward'));
        $betLoseAmount = Bet::query()
            ->where('user_id', $userId)
            ->where('is_demo', Bet::REAL_TYPE)
            ->where('is_result', Bet::EXECUTED_RESULT)
            ->where('result', Bet::LOSE)
            ->sum(DB::raw('amount'));    
        $commisionAmount = HistoryCommission::query()
            ->where('user_id', $userId)
            ->sum('value');
        $buyCommissionAmount = TransactionDetail::query()
            ->where('user_id', $userId)
            ->whereIn('type', TransactionDetail::TRANSACTION_TYPE_COMMISSION)
            ->where('status', TransactionDetail::TRANSACTION_STATUS_COMPLETED)
            ->sum(DB::raw('amount'));
        // $buyTicketAmount = TicketHistory::query()
        //     ->where('user_id', $userId)

        $useAmount = $depositAmount - $withdrawAmount + $betWinAmount - $betLoseAmount + $commisionAmount - $buyCommissionAmount;
        // $diff = $useAmount > ($user->real_balance + $user->usdt_balance);
        // if ($diff) {
        //     $value = $useAmount - ($user->real_balance + $user->usdt_balance);
        //     Log::info('update ' . $value . '$ for user ' . $userId);
        //     User::query()->where('id', $userId)->update([
        //         'real_balance' => DB::raw('`real_balance` + ' . $value),
        //     ]);
        // }

        $this->info('Use Balance: ' . $useAmount);
        $this->info('Now Balance: ' . $user->real_balance + $user->usdt_balance);

        return Command::SUCCESS;
    }
}

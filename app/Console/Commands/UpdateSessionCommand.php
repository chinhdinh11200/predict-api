<?php

namespace App\Console\Commands;

use App\Console\Kernel;
use App\Models\Chart;
use App\Models\Coin;
use App\Models\LastResult;
use App\Services\Common\SessionServiceCommon;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSessionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = Kernel::SCHEDULE_UPDATE_SESSION;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CMD] Update session.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = $this->option('limit') ?? 50;
        $results = [];
        $lastResult = LastResult::query()->orderByDesc('id')->first();
        try {
            DB::beginTransaction();
            $timestampInSeconds = $lastResult ? $lastResult->end_time / 1000 : 1711700400;
            $nextTime = Carbon::createFromTimestampUTC($timestampInSeconds);

            for ($i = 0; $i < $limit; $i++) {
                $results[] = [
                    'start_time' => $nextTime->timestamp * 1000,
                    'end_time' => $nextTime->addSeconds(30)->timestamp * 1000,
                    'result' => LastResult::DOWN,
                    'is_bet_session' => ($i + ($lastResult ? $lastResult->id : 0) + 1) % 2 == 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }//end for

            LastResult::query()->insert($results);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
            Log::error($exception->getMessage());
        }//end try

        $this->info('Update successful!');

        return Command::SUCCESS;
    }
}

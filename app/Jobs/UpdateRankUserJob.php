<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserRelationship;
use App\Services\User\BetService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateRankUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $user)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $betService = BetService::getInstance();
        $userRelationship = UserRelationship::query()->where('user_id', $this->user->id)->first();
        $volumeOfWeek = $betService->getVolumeCurrentWeek($this->user);
        $numberChildAgency = $betService->countChildAgencyUserNotNone($userRelationship);
        $level = $betService->getRankByVolumeAndAgency($volumeOfWeek, $numberChildAgency);
        if ($level <= $this->user->level) return;
        User::query()->where([
            'id' => $this->user->id,
        ])->update([
            'level' => $level,
        ]);
    }
}

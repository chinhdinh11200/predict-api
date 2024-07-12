<?php

namespace App\Services\User;

use App\Models\Chart;
use App\Models\LastResult;
use App\Services\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ChartService extends Service
{
    private $limitChart = 100;  
    private $limitLastResult = 80;  
    private const ONE_BLOCK_RESULT = 20;  
    /**
     * Charts
     *
     * @return Builder[]|Collection
     */
    public function chart()
    {
        return LastResult::query()->with('chart')->where('end_time', '<=', time() * 1000)->orderBy('id', 'DESC')->limit($this->limitChart)->get();
    }

    /**
     * Last results
     *
     * @return array
     */
    public function lastResult(): array
    {
        $lastChartEndTime = Chart::query()->max('end_time');
        if (request()->get('is_mobile')) {
            $this->limitLastResult = 40;
        }

        $lastResult = LastResult::query()
            ->orderBy('updated_at', 'DESC')
            ->first();
        if ($lastResult) {
            $plus = $lastResult->id % self::ONE_BLOCK_RESULT;
            $this->limitLastResult += $plus;
        }
        $lastResults = LastResult::query()->where('end_time', '<=', $lastChartEndTime)->orderBy('id', 'DESC')->limit($this->limitLastResult)->get();

        $totalUp = $lastResults->where('result', LastResult::UP)->count();
        $totalDown = $lastResults->where('result', LastResult::DOWN)->count();

        return [
            'up' => $totalUp,
            'down' => $totalDown,
            'data' => $lastResults,
        ];
    }
}

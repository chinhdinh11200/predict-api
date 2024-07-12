<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Indicators;
use App\Models\TraderSentiments;
use Illuminate\Http\JsonResponse;

class IndicatorController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = Indicators::query()->select(['sell', 'buy', 'neutral', 'key'])->get();

        return $this->sendSuccessResponse($data);
    }

    /**
     * @return JsonResponse
     */
    public function getTraderSentiments(): JsonResponse
    {
        $data = TraderSentiments::query()->select(['sell', 'buy'])->first();

        return $this->sendSuccessResponse($data);
    }
}

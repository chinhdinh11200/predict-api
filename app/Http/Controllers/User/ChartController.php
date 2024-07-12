<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Chart\ChartResource;
use App\Http\Resources\User\Chart\LastResultResource;
use App\Services\User\BetService;
use App\Services\User\ChartService;
use Illuminate\Http\JsonResponse;

class ChartController extends Controller
{
    /**
     * Chart
     *
     * @return JsonResponse
     */
    public function chart()
    {
        $data = ChartService::getInstance()->chart();

        return $this->sendSuccessResponse(ChartResource::collection($data));
    }

    /**
     * Get last result
     *
     * @return JsonResponse
     */
    public function lastResult(): JsonResponse
    {
        $data = ChartService::getInstance()->lastResult();

        return $this->sendSuccessResponse(new LastResultResource($data));
    }

    /**
     * @return JsonResponse
     */
    public function getCurrentSession(): JsonResponse
    {
        $data = BetService::getInstance()->getSession();
        $isBetSession = (bool)$data;
        return $this->sendSuccessResponse([
            'is_bet_session' => $isBetSession
        ]);
    }
}

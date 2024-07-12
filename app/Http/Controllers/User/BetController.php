<?php

namespace App\Http\Controllers\User;

use App\Exceptions\InputException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\BetRequest;
use App\Services\User\BetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\User\Bet\BetCollection;
use App\Http\Resources\User\Bet\TradeCollection;

class BetController extends Controller
{
    /**
     * bet
     *
     * @param BetRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function bet(BetRequest $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $data = BetService::getInstance()->withUser($currentUser)->bet($request->all());

        return $this->sendSuccessResponse($data);
    }

    /**
     * Bet history
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $page = $request->get('page') ?? 1;
        $perPage = $request->get('per_page') ?? 10;
        $isResult = $request->get('is_result') ?? null;
        $startTime = $request->get('start_time') ?? null;
        $endTime = $request->get('end_time') ?? date('Y/m/d');

        $data = BetService::getInstance()->withUser($currentUser)->history($page, $perPage, $startTime, $endTime, $isResult);

        return $this->sendSuccessResponse(new BetCollection($data));
    }

    /**
     * Trade history
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function tradeHistory(Request $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $page = $request->get('page') ?? 1;
        $perPage = $request->get('per_page') ?? 10;
        $startTime = $request->get('start_time') ?? null;
        $endTime = $request->get('end_time') ?? date('Y/m/d');

        $data = BetService::getInstance()->withUser($currentUser)->tradeHistory($page, $perPage, $startTime, $endTime);

        return $this->sendSuccessResponse(new TradeCollection($data));
    }

    public function updateUserBalance(Request $request) {
        $user = auth()->guard()->user();
        $sessionId = $request->session_id ?? null;
        $data = BetService::getInstance()->withUser($user)->updateUserBalance($sessionId);

        return $this->sendSuccessResponse($data);
    }
}

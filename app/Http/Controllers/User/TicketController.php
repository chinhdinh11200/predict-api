<?php

namespace App\Http\Controllers\User;

use App\Exceptions\InputException;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\RewardPrizeCollection;
use App\Http\Resources\User\SpinWheelResource;
use App\Services\User\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Buy ticket
     *
     * @param Request $request
     * @return JsonResponse
     * @throws InputException
     */
    public function buyTicket(Request $request): JsonResponse
    {
        $user = auth()->guard()->user();
        $total = $request->get('total');
        $data = TicketService::getInstance()->withUser($user)->buyTicket($total);

        return $this->sendSuccessResponse($data, trans('response.buy_ticket_success'));
    }

    /**
     * Spin wheel
     *
     * @return JsonResponse
     * @throws InputException
     */
    public function spinWheel(): JsonResponse
    {
        $user = auth()->guard()->user();
        $data = TicketService::getInstance()->withUser($user)->spinWheel();

        return $this->sendSuccessResponse(new SpinWheelResource($data), trans(''));
    }

    /**
     * History
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function history(Request $request): JsonResponse
    {
        $user = auth()->guard()->user();
        $page = $request->get('page') ?? 1;
        $perPage = $request->get('per_page') ?? 10;
        $data = TicketService::getInstance()->withUser($user)->history($page, $perPage);

        return $this->sendSuccessResponse(new RewardPrizeCollection($data));
    }
}

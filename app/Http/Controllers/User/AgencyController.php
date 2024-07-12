<?php

namespace App\Http\Controllers\User;

use App\Exceptions\InputException;
use App\Http\Controllers\Controller;
use App\Services\User\AgencyService;
use Illuminate\Http\JsonResponse;

class AgencyController extends Controller
{
    /**
     * Buy vip agency
     *
     * @return JsonResponse
     * @throws InputException
     */
    public function buyVip(): JsonResponse
    {
        $user = auth()->guard()->user();
        $data = AgencyService::getInstance()->withUser($user)->buyVip();

        return $this->sendSuccessResponse($data, trans('response.agency.success'));
    }

    /**
     * Buy normal agency
     *
     * @return JsonResponse
     * @throws InputException
     */
    public function buyNormal(): JsonResponse
    {
        $user = auth()->guard()->user();
        $data = AgencyService::getInstance()->withUser($user)->buyNormal();

        return $this->sendSuccessResponse($data, trans('response.agency.success'));
    }
}

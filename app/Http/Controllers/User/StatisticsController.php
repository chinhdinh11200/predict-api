<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\StatisticsService;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    /**
     * Get statistics
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = auth()->guard()->user();
        $data = StatisticsService::getInstance()->withUser($user)->index();

        return $this->sendSuccessResponse($data);
    }
}

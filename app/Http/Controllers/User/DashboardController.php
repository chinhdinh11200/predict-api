<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\BetService;
use App\Services\User\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $data = DashboardService::getInstance()->withUser($currentUser)->dashboard();

        return $this->sendSuccessResponse($data);
    }
}

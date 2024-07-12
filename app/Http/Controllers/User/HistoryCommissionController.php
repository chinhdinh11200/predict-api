<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Commission\HistoryCommissionCollection;
use App\Services\User\HistoryCommissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryCommissionController extends Controller
{
    /**
     * HistoryCommissionController constructor
     */
    public function __construct()
    {
    }

    /**
     * HistoryCommission list
     * 
     * @param Request $request
     * @return JsonResponse 
     */
    public function history(Request $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $page = $request->get('page') ?? 1;
        $perPage = $request->get('per_page') ?? 10;
        $type = $request->get('type') ?? null;
        $data = HistoryCommissionService::getInstance()->withUser($currentUser)->history($page, $perPage, $type);

        return $this->sendSuccessResponse(new HistoryCommissionCollection($data));
    }
}

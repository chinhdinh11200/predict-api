<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Pool\HistoryPoolCollection;
use App\Services\User\PoolService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PoolController extends Controller
{
    /**
     * Pool constructor
     */
    public function __construct()
    {
    }

    /**
     * History pool list
     */
    public function history(Request $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $page = $request->get('page') ?? 1;
        $perPage = $request->get('per_page') ?? 10;
        $type = $request->get('type') ?? null;
        $data = PoolService::getInstance()->withUser($currentUser)->history($page, $perPage, $type);
        
        return $this->sendSuccessResponse(new HistoryPoolCollection($data));
    }
}

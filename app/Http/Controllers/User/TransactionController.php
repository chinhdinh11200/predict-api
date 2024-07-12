<?php

namespace App\Http\Controllers\User;

use App\Exceptions\InputException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Transaction\TransactionInternalRequest;
use App\Http\Requests\User\Transaction\TransactionRequest;
use App\Http\Resources\User\HistoryTransactionCollection;
use App\Services\User\TransactionService;
use App\Services\User\WalletDepositUserService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * TransactionController constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Deposit money
     *
     * @param TransactionInternalRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function internalDepositMoney(TransactionInternalRequest $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $amount = $request->get('amount');
        $data = TransactionService::getInstance()->withUser($currentUser)->internalDepositMoney(floatval($amount));

        return $this->sendSuccessResponse($data, trans('transaction.usdt_to_live_success'));
    }

    /**
     * Withdraw money
     *
     * @param TransactionInternalRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function internalWithdrawMoney(TransactionInternalRequest $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $amount = $request->get('amount');
        $data = TransactionService::getInstance()->withUser($currentUser)->internalWithdrawMoney(floatval($amount));

        return $this->sendSuccessResponse($data, trans('transaction.live_to_usdt_success'));
    }

    /**
     * Withdraw money
     *
     * @param TransactionRequest $request
     * @return JsonResponse
     * @throws InputException
     */
    public function withdrawTransaction(TransactionRequest $request): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $inputs = $request->only(['amount', 'address', 'note']);
        $data = TransactionService::getInstance()->withUser($currentUser)->withdrawTransaction($inputs);

        return $this->sendSuccessResponse($data, trans('transaction.success'));
    }

    /**
     *  get wallet address
     *
     * @return JsonResponse
     */
    public function getWalletDeposit(): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $data = WalletDepositUserService::getInstance()->withUser($currentUser)->getWalletByUserId($currentUser->id);

        return $this->sendSuccessResponse($data?->address);
    }

    /**
     *    generateWallet
     *
     * @throws GuzzleException
     */
    public function generateWallet(): JsonResponse
    {
        $currentUser = auth()->guard()->user();
        $data = WalletDepositUserService::getInstance()->withUser($currentUser)->getWalletByUserId($currentUser->id);
        if ($data) {
            return $this->sendErrorResponse(__('transaction.wallet_fail'));
        }

        $wallet = WalletDepositUserService::getInstance()->withUser($currentUser)->generateWalletDeposit($currentUser->id);
        if (!$wallet) {
            return $this->sendErrorResponse(__('transaction.generate_fail'));
        }
        return $this->sendSuccessResponse(['address' => $wallet->address]);
    }

    /**
     * History
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
        $data = TransactionService::getInstance()->withUser($currentUser)->history($page, $perPage, $type);

        return $this->sendSuccessResponse(new HistoryTransactionCollection($data));
    }
}

<?php

namespace App\Admin\Forms;

use App\Models\Pool;
use App\Models\SystemWallet;
use Dcat\Admin\Form;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PoolForm extends Form
{
    public const DEPOSIT = 1;
    public const WITHDRAW = 0;

    public function update(
        $id,
        ?array $data = null,
        $redirectTo = null
    ) {
        DB::beginTransaction();
        try {
            if ($data) {
                $this->request->replace($data);
            }
            $data = $data ?: $this->request->all();
            $valueChange = floatval($data['value_change'] ?? 0);
            
            if ($response = $this->beforeUpdate($id, $data)) {
                return $this->sendResponse($response);
            }
            if ($valueChange) {
                $systemWallet = SystemWallet::query()->first();
                if ($data['type'] == self::DEPOSIT) {
                    $updated = SystemWallet::query()
                    ->where('id', $systemWallet->id)
                    ->where('value', '>=', $valueChange)
                    ->update([
                        'value' => DB::raw('value - ' . $valueChange),
                    ]);
                    if ($updated <= 0) {
                        throw new \Exception(trans('admin.pool.wallet_not_enough'));
                    }

                    Pool::query()
                        ->where('id', $id)
                        ->update([
                            'value' => DB::raw('value + ' . $valueChange),
                        ]);
                } else {
                    $updated = Pool::query()
                        ->where('id', $id)
                        ->where('value', '>=', $valueChange)
                        ->update([
                            'value' => DB::raw('value - ' . $valueChange),
                        ]);
                    if ($updated <= 0) {
                        throw new \Exception(trans('admin.pool.pool_not_enough'));
                    }
                    $systemWallet->value += $valueChange;
                    $systemWallet->save();
                }
            }
            $url = $this->getRedirectUrl($id, $redirectTo);
            DB::commit();

            return $this->sendResponse(
                $this->response()
                    ->success(trans('admin.update_succeeded'))
                    ->redirectIf($url !== false, $url)
                    ->refreshIf($url === false)
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            $response = $this->handleException($e);

            if ($response instanceof Response) {
                return $response;
            }

            return $this->sendResponse(
                $this->response()
                    ->error(trans('admin.update_failed'))
                    ->withExceptionIf($e->getMessage(), $e)
            );
        }
    }
}

<?php

namespace App\Admin\Forms;

use App\Models\TransactionDetail;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Widgets\Form;
use Dcat\Admin\Traits\LazyWidget;

class ApproveTransactionForm extends Form implements LazyRenderable
{
    use LazyWidget;

    public function handle(array $input)
    {
        return '';
    }

    public function default()
    {
        return [
            'status' => TransactionDetail::TRANSACTION_STATUS_COMPLETED,
            'username' => $this->payload['username'] ?? '',
            'amount' => $this->payload['amount'] ?? 0,
        ];
    }

    public function form()
    {
        $this->resetButton(false);
        $this->action("/transaction-detail/{$this->payload['key']}/edit-status");
        $this->text('username', trans('admin.transaction_detail.user_id'))
            ->disable(true);
        $this->text('amount', trans('admin.transaction_detail.amount'))->disable(true);
        $transaction = TransactionDetail::query()->where('id', $this->payload['key'])->first();
        if ($transaction && $transaction->status === TransactionDetail::TRANSACTION_STATUS_PENDING && $transaction->type === TransactionDetail::TRANSACTION_TYPE_WITHDRAW) {
            $this->select('status', trans('admin.transaction_detail.status'))->options([
                TransactionDetail::TRANSACTION_STATUS_COMPLETED => trans('admin.transaction_detail.approve.' . TransactionDetail::TRANSACTION_STATUS_COMPLETED),
                TransactionDetail::TRANSACTION_STATUS_CANCELLED => trans('admin.transaction_detail.approve.' . TransactionDetail::TRANSACTION_STATUS_CANCELLED),
            ]);
        } else {
            $this->select('status', trans('admin.transaction_detail.status'))->options([
                TransactionDetail::TRANSACTION_STATUS_PENDING => trans('admin.transaction_detail.status_lang.' . TransactionDetail::TRANSACTION_STATUS_PENDING),
                TransactionDetail::TRANSACTION_STATUS_COMPLETED => trans('admin.transaction_detail.status_lang.' . TransactionDetail::TRANSACTION_STATUS_COMPLETED),
                TransactionDetail::TRANSACTION_STATUS_FAILED => trans('admin.transaction_detail.status_lang.' . TransactionDetail::TRANSACTION_STATUS_FAILED),
                TransactionDetail::TRANSACTION_STATUS_CANCELLED => trans('admin.transaction_detail.status_lang.' . TransactionDetail::TRANSACTION_STATUS_CANCELLED),
            ])->disable();
        }
    }
}

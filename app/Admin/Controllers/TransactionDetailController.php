<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\ApproveTransactionForm;
use App\Admin\Repositories\TransactionDetail;
use App\Helpers\NumberHelper;
use App\Models\TransactionDetail as ModelsTransactionDetail;
use App\Models\User;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Traits\HasFormResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionDetailController extends AdminController
{
    use HasFormResponse;

    public function edit($id, Content $content)
    {
        $body = $this->form()->edit($id);
        if (request()->get('_dialog_form_')) {
            $body = $this->formStatus()->edit($id);
        }

        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['edit'] ?? trans('admin.edit'))
            ->body($body);
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();
            $status = request()->get('status') ??  null;
            switch ($status) {
                case ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED:
                    $url = config('app.scan_url') . "/confirm-send-token";
                    $res = Http::withHeaders([
                        'x-api-token' => config('app.scan_api_key'),
                    ])->post($url, [
                        "transaction_id" => $id,
                    ]);
                    $data = $res->json()['data'];
                    if (isset($data['success']) && $data['success'] == false) {
                        throw new \Exception("Thất bại");
                    }
                    break;
                case ModelsTransactionDetail::TRANSACTION_STATUS_CANCELLED;
                    $transaction = ModelsTransactionDetail::query()->where('id', $id)->first();
                    $money = $transaction->amount ?? 0;
                    User::query()
                        ->where('id', $transaction->user_id)->update([
                            'usdt_balance' => DB::raw('`usdt_balance` + ' . $money),
                        ]);
                    DB::commit();
                    return $this->form()->update($id);
                    break;
                default:
                    return $this->form()->update($id);
                    break;
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendResponse(new JsonResponse([
                "message" => trans('admin.update_failed'),
                "type" => "error",
                "then" => [
                    "action" => "redirect",
                    "value" => route('dcat.admin.transaction-detail.index'),
                ]
            ]));
        }
    }

    public function editStatus($id)
    {
        try {
            DB::beginTransaction();
            $status = request()->get('status') ??  null;
            switch ($status) {
                case ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED:
                    $url = config('app.scan_url') . "/confirm-send-token";
                    $res = Http::withHeaders([
                        'x-api-token' => config('app.scan_api_key'),
                    ])->post($url, [
                        "transaction_id" => $id,
                    ]);
                    $data = $res->json()['data'];
                    if (isset($data['success']) && $data['success'] == false) {
                        throw new \Exception("Thất bại");
                    }
                    return $this->sendResponse(new JsonResponse([
                        "message" => trans('admin.update_succeeded'),
                        "type" => "success",
                        "then" => [
                            "action" => "redirect",
                            "value" => route('dcat.admin.transaction-detail.index'),
                        ]
                    ]));
                    break;
                case ModelsTransactionDetail::TRANSACTION_STATUS_CANCELLED;
                    $transaction = ModelsTransactionDetail::query()->where('id', $id)->first();
                    $money = $transaction->amount ?? 0;
                    User::query()
                        ->where('id', $transaction->user_id)->update([
                            'usdt_balance' => DB::raw('`usdt_balance` + ' . $money),
                        ]);
                    DB::commit();
                    return $this->form()->update($id);
                    break;
                default:
                    return $this->form()->update($id);
                    break;
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendResponse(new JsonResponse([
                "message" => trans('admin.update_failed'),
                "type" => "error",
                "then" => [
                    "action" => "redirect",
                    "value" => route('dcat.admin.transaction-detail.index'),
                ]
            ]));
        }
    }

    public function title()
    {
        return trans('admin.transaction_detail.title.list');
    }

    public function grid()
    {
        return Grid::make(new TransactionDetail(['user']), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('user.username', trans('admin.transaction_detail.user_id'));
            $grid->column('username', trans('admin.transaction_detail.username'));
            $grid->column('amount', trans('admin.transaction_detail.amount'))->display(function ($amount) {
                return NumberHelper::admin_number_format_no_zero(floatval($amount));
            })->sortable();
            $grid->column('fee', trans('admin.transaction_detail.fee'))->display(function ($fee) {
                return NumberHelper::admin_number_format_no_zero(floatval($fee));
            })->sortable();
            $grid->column('address', trans('admin.transaction_detail.address'));
            $grid->column('type', trans('admin.transaction_detail.type'))->display(function ($type) {
                return trans("admin.transaction_detail.type_lang.{$type}");
            });
            $grid->column('status', trans('admin.transaction_detail.status'))
                ->display(function ($status) {
                    return trans("admin.transaction_detail.status_lang.{$status}");
                });
            $grid->column('approve', ' ')
                ->if(function () {
                    return $this->status === ModelsTransactionDetail::TRANSACTION_STATUS_PENDING && $this->type === ModelsTransactionDetail::TRANSACTION_TYPE_WITHDRAW;
                })
                ->display(function () {
                    return trans('admin.transaction_detail.approve.' . ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED);
                })
                ->label('success')
                ->modal(function (Grid\Displayers\Modal $modal) {
                    $modal->title(' ');
                    $modal->icon('');
                    return ApproveTransactionForm::make()->payload([
                        'username' => $this->user->username,
                        'amount' => $this->amount,
                        'status' => $this->status,
                    ]);
                })
                ->endif();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $users = User::query()->pluck('username', 'id')->toArray();
                $filter->equal('user.id', trans('admin.transaction_detail.user_filter_label'))->width(2)->select($users);
                $types = trans('admin.transaction_detail.type_lang');
                $filter->equal('type', trans('admin.transaction_detail.type_filter_label'))->width(2)->select($types);
            });

            $grid->quickSearch('user.username', 'username', 'tx', 'address', 'note');
            $grid->disableDeleteButton();
            $grid->disableEditButton();
        });
    }

    public function detail($id)
    {
        return Show::make($id, new TransactionDetail(['user']), function (Show $show) {
            $show->field('id', trans('admin.transaction_detail.id'));
            $show->field('user.username', trans('admin.transaction_detail.user_id'));
            $show->field('tx', trans('admin.transaction_detail.tx'));
            $show->field('username', trans('admin.transaction_detail.username'));
            $show->field('address', trans('admin.transaction_detail.address'));
            $show->field('amount', trans('admin.transaction_detail.amount'))->as(function ($amount) {
                return NumberHelper::admin_number_format_no_zero(floatval($amount));
            });
            $show->field('fee', trans('admin.transaction_detail.fee'))->as(function ($fee) {
                return NumberHelper::admin_number_format_no_zero(floatval($fee));
            });
            $show->field('note', trans('admin.transaction_detail.note'));
            $show->field('type', trans('admin.transaction_detail.type'))->as(function ($type) {
                return trans("admin.transaction_detail.type_lang.{$type}");
            });
            $show->field('status', trans('admin.transaction_detail.status'))->as(function ($status) {
                return trans("admin.transaction_detail.status_lang.{$status}");
            });

            $show->disableEditButton();
            $show->disableDeleteButton();
        });
    }

    public function form()
    {
        return Form::make(new TransactionDetail(['user']), function (Form $form) {
            $id = $form->getKey();
            if ($id) {
                $form->text('id', trans('admin.transaction_detail.id'))->disable(true);
                $form->text('user.username', trans('admin.transaction_detail.user_id'))->disable(true);
            } else {
                $users = User::query()->pluck('username', 'id')->toArray();
                $form->select('user_id', trans('admin.transaction_detail.user_id'))->options($users)->required();
            }
            $form->text('tx', trans('admin.transaction_detail.tx'));
            $form->text('username', trans('admin.transaction_detail.username'));
            $form->text('address', trans('admin.transaction_detail.address'));
            $form->number('amount', trans('admin.transaction_detail.amount'))->rules('required|numeric|min:0');
            $form->number('fee', trans('admin.transaction_detail.fee'))->rules('required|numeric|min:0');
            $form->text('note', trans('admin.transaction_detail.note'));
            $types = trans('admin.transaction_detail.type_lang');
            $form->select('type', trans('admin.transaction_detail.type'))->options($types)->required();
            $status = trans('admin.transaction_detail.status_lang');
            $form->select('status', trans('admin.transaction_detail.status'))->options($status)->required();
        });
    }

    public function formStatus()
    {
        return Form::make(new TransactionDetail(['user']), function (Form $form) {
            $id = $form->getKey();
            if ($id) {
                $form->text('user.username', trans('admin.transaction_detail.user_id'))->disable(true);
                $form->text('amount', trans('admin.transaction_detail.amount'))->disable(true);
                $transaction = ModelsTransactionDetail::query()->where('id', $id)->first();
                if ($transaction && $transaction->status === ModelsTransactionDetail::TRANSACTION_STATUS_PENDING && $transaction->type === ModelsTransactionDetail::TRANSACTION_TYPE_WITHDRAW) {
                    $form->select('status', 'Type')->options([
                        ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED => trans('admin.transaction_detail.status_lang.' . ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED),
                        ModelsTransactionDetail::TRANSACTION_STATUS_CANCELLED => trans('admin.transaction_detail.status_lang.' . ModelsTransactionDetail::TRANSACTION_STATUS_CANCELLED),
                    ]);
                } else {
                    $form->select('status', 'Type')->options([
                        ModelsTransactionDetail::TRANSACTION_STATUS_PENDING => trans('admin.transaction_detail.status_lang.' . ModelsTransactionDetail::TRANSACTION_STATUS_PENDING),
                        ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED => trans('admin.transaction_detail.status_lang.' . ModelsTransactionDetail::TRANSACTION_STATUS_COMPLETED),
                        ModelsTransactionDetail::TRANSACTION_STATUS_FAILED => trans('admin.transaction_detail.status_lang.' . ModelsTransactionDetail::TRANSACTION_STATUS_FAILED),
                        ModelsTransactionDetail::TRANSACTION_STATUS_CANCELLED => trans('admin.transaction_detail.status_lang.' . ModelsTransactionDetail::TRANSACTION_STATUS_CANCELLED),
                    ])->disable();
                }
            }
        });
    }
}

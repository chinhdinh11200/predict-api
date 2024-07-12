<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Traits\HasFormResponse;
use App\Admin\Repositories\User;
use App\Helpers\NumberHelper;
use App\Models\User as ModelsUser;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends AdminController
{
    use HasFormResponse;

    protected $title;

    public function title()
    {
        return trans('admin.users.title.list');
    }

    /**
     * Edit setting
     *
     * @param  mixed  $id
     * @param  Content  $content
     * @return Content
     */
    public function editSetting($id, Content $content)
    {
        return $content
            ->body($this->formSetting()->edit($id));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Update setting
     *
     * @param  mixed  $id
     * @param  Content  $content
     * @return Content
     */
    public function updateSetting(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            UserSetting::query()
                ->where('user_id', $id)
                ->updateOrCreate([
                    'user_id' => $id,
                ], [
                    'is_revert' => $request->get('is_revert') ?? UserSetting::NONE_REVERT,
                    'is_marketing' => $request->get('is_marketing') ?? UserSetting::NONE_MARKETING,
                    'is_lock_transaction' => $request->get('is_lock_transaction') ?? UserSetting::NONE_LOCK_TRANSACTION,
                ]);
            DB::commit();

            return $this->sendResponse(new JsonResponse([
                "message" => "Update succeeded !",
                "type" => "success",
                "then" => [
                    "action" => "redirect",
                    "value" => route('dcat.admin.user.index'),
                ]
            ]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception]);
            DB::rollBack();

            return $this->sendResponse(new JsonResponse([
                "message" => $exception->getMessage(),
                "type" => "error",
                "then" => [
                    "action" => "redirect",
                    "value" => route('dcat.admin.user.index'),
                ]
            ]));
        }
    }

    /**
     * Update level
     *
     * @param  mixed  $id
     * @param  Content  $content
     * @return Content
     */
    public function updateLevel(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            if (!$request->get('level')) {
                throw new \Exception(trans('validation.required'));
            }
            ModelsUser::query()
                ->where('id', $id)
                ->update([
                    'level' => $request->get('level'),
                ]);
            DB::commit();

            return $this->sendResponse(new JsonResponse([
                "message" => "Update succeeded !",
                "type" => "success",
                "then" => [
                    "action" => "redirect",
                    "value" => route('dcat.admin.user.index'),
                ]
            ]));
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), [$exception]);
            DB::rollBack();

            return $this->sendResponse(new JsonResponse([
                "message" => $exception->getMessage(),
                "type" => "error",
                "then" => [
                    "action" => "redirect",
                    "value" => route('dcat.admin.user.index'),
                ]
            ]));
        }
    }

    public function editStatus($id) {
        return $this->formStatus()->update($id);
    }

    public function grid()
    {
        return Grid::make(new User(['setting']), function (Grid $grid) {
            $grid->setName('userList');
            $grid->quickSearch('fullname', 'username', 'refcode', 'email');
            $grid->column('id', trans('admin.users.id'))->sortable();
            $grid->column('username', trans('admin.users.username'));
            $grid->column('avatar', trans('admin.users.avatar'))->image('', 60, 60);
            $grid->column('email', trans('admin.users.email'));
            $grid->column('refcode', trans('admin.users.refcode'));
            $grid->column('real_balance', trans('admin.users.real_balance'))->sortable()->display(function ($realBalance) {
                return NumberHelper::admin_number_format_no_zero(floatval($realBalance));
            });

            $grid->column('virtual_balance', trans('admin.users.virtual_balance'))->sortable()->display(function ($realBalance) {
                return NumberHelper::admin_number_format_no_zero(floatval($realBalance));
            });
            $grid->column('usdt_balance', trans('admin.users.usdt_balance'))->sortable()->display(function ($realBalance) {
                return NumberHelper::admin_number_format_no_zero(floatval($realBalance));
            });
            $grid->column('status', trans('admin.users.status'))
                ->radio([
                    ModelsUser::STATUS_ACTIVE => trans('admin.users.status_lang.' . ModelsUser::STATUS_ACTIVE),
                    ModelsUser::STATUS_INACTIVE => trans('admin.users.status_lang.' . ModelsUser::STATUS_INACTIVE),
                    ModelsUser::STATUS_LOCK => trans('admin.users.status_lang.' . ModelsUser::STATUS_LOCK),
                ], true)
                ->display(function ($status) {
                    $routeEdit = route('dcat.admin.user.update', $this->id);
                    $routeEditStatus = route('dcat.admin.user.editStatus', $this->id);
                    $statusLink = str_replace($routeEdit, $routeEditStatus, $status);
                    return $statusLink;
                });

            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $grid->showEditButton();
        });
    }

    protected function detail($id)
    {
        return Show::make($id, new User(), function (Show $show) {
            $show->field('id');
            $show->field('username');
            $show->field('fullname');

            $show->field('avatar', __('admin.avatar'))->image(config('app.url') . "/storage/upload");

            $show->field('email');
            $show->field('refcode');
            $show->field('real_balance', trans('admin.users.real_balance'))->as(function ($balance) {
                return NumberHelper::admin_number_format_no_zero(floatval($balance));
            });
            $show->field('virtual_balance', trans('admin.users.virtual_balance'))->as(function ($balance) {
                return NumberHelper::admin_number_format_no_zero(floatval($balance));
            });
            $show->field('usdt_balance', trans('admin.users.usdt_balance'))->as(function ($balance) {
                return NumberHelper::admin_number_format_no_zero(floatval($balance));
            });
            $show->field('level', trans('admin.users.level'));
            $show->field('agency_status', trans('admin.users.agency_status'))->as(function ($agencyStatus) {
                return trans("admin.users.agency_status_lang.{$agencyStatus}");
            });
            $show->field('total_tickets', trans('admin.users.total_tickets'));
            $show->field('status', trans('admin.users.status'))->as(function ($status) {
                return trans("admin.users.status_lang.{$status}");
            });

            $show->disableDeleteButton();
            // $show->disableEditButton();
        });
    }

    public function form()
    {
        return Form::make(new User(['setting']), function (Form $form) {
            $form->text('username', trans('admin.users.title.update'))->disable(true);
            $form->select('setting.is_marketing', trans('admin.users.setting.marketing_title'))->options([
                UserSetting::MARKETING => trans("admin.users.setting.status_marketing." . UserSetting::MARKETING),
                UserSetting::NONE_MARKETING => trans("admin.users.setting.status_marketing." . UserSetting::NONE_MARKETING),
            ]);
            $form->select('setting.is_revert', trans('admin.users.setting.revert_title'))->options([
                UserSetting::REVERT => trans("admin.users.setting.status_revert." . UserSetting::REVERT),
                UserSetting::NONE_REVERT => trans("admin.users.setting.status_revert." . UserSetting::NONE_REVERT),
            ]);
            $form->select('setting.is_lock_transaction', trans('admin.users.setting.transaction_title'))->options([
                UserSetting::LOCK_TRANSACTION => trans("admin.users.setting.status_transaction." . UserSetting::LOCK_TRANSACTION),
                UserSetting::NONE_LOCK_TRANSACTION => trans("admin.users.setting.status_transaction." . UserSetting::NONE_LOCK_TRANSACTION),
            ]);
            $form->select('level', trans('admin.users.level'))->required()->options([
                0,1,2,3,4,5,6,7
            ]);

            $form->disableDeleteButton();
        });
    }

    public function formStatus()
    {
        return Form::make(new User(), function (Form $form) {
            $form->select('status', 'status')->options([
                ModelsUser::STATUS_ACTIVE => trans("admin.users.status_lang." . ModelsUser::STATUS_ACTIVE),
                ModelsUser::STATUS_INACTIVE => trans("admin.users.status_lang." . ModelsUser::STATUS_INACTIVE),
                ModelsUser::STATUS_LOCK => trans("admin.users.status_lang." . ModelsUser::STATUS_LOCK),
            ]);
        });
    }
}

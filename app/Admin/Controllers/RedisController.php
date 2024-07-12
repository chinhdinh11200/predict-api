<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\RedisUserOnline;
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;
use Illuminate\Contracts\Support\Renderable;

class RedisController extends AdminController
{
    public function title()
    {
        return trans('admin.users.title.user_online');
    }

    public function index(Content $content)
    {
        $route = route('dcat.admin.user-online.render-table');
        $socketUrl = config('app.scan_url');
        Admin::js('https://cdn.socket.io/4.7.5/socket.io.min.js');
        Admin::script(
            <<<JS
                const socket = io('{$socketUrl}', {
                    auth: {
                        serverOffset: 0,
                    },
                    ackTimeout: 10000,
                    retries: 3,
                    transports: ["websocket"],
                    query: {
                        token: 1234,
                    }
                });

                socket.on('BIFIX_USER_ONLINE', () => {
                    $.ajax({
                        url: '{$route}',
                        type: 'GET',
                        success: function(data) {
                            $('#userOnline .card-body').html(data);
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    })
                });
            JS
        );

        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    public function grid()
    {
        return Grid::make(new RedisUserOnline(), function (Grid $grid) {
            $grid->wrap(function(Renderable $view) {
                $card = Card::make($view);
                $card->id('userOnline');

                return $card;
            });
            $grid->id;
            $grid->column('username', trans('admin.users.username'));
            $grid->column('avatar', trans('admin.users.avatar'))->image('', 60, 60);
            $grid->column('email', trans('admin.users.email'));

            $grid->disableActions(true);
            $grid->disableCreateButton();
        });
    }

    public function renderTable()
    {
        return Grid::make(new RedisUserOnline(), function (Grid $grid) {
            $grid->id;
            $grid->column('username', trans('admin.users.username'));
            $grid->column('avatar', trans('admin.users.avatar'))->image('', 60, 60);
            $grid->column('email', trans('admin.users.email'));

            $grid->disableActions(true);
            $grid->disableCreateButton();
        })->render();
    }
}

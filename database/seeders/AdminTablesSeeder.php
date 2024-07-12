<?php

namespace Database\Seeders;

use Dcat\Admin\Models\Administrator;
use Dcat\Admin\Models\Menu;
use Dcat\Admin\Models\Permission;
use Dcat\Admin\Models\Role;
use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = date('Y-m-d H:i:s');

        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username'   => 'admin',
            'password'   => bcrypt('admin'),
            'name'       => 'Administrator',
            'created_at' => $createdAt,
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name'       => 'Administrator',
            'slug'       => Role::ADMINISTRATOR,
            'created_at' => $createdAt,
        ]);

        // add role to user.
        Administrator::first()->roles()->detach(Role::first());
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'id'          => 1,
                'name'        => 'Auth management',
                'slug'        => 'auth-management',
                'http_method' => '',
                'http_path'   => '',
                'parent_id'   => 0,
                'order'       => 1,
                'created_at'  => $createdAt,
            ],
            [
                'id'          => 2,
                'name'        => 'Users',
                'slug'        => 'users',
                'http_method' => '',
                'http_path'   => '/auth/users*',
                'parent_id'   => 1,
                'order'       => 2,
                'created_at'  => $createdAt,
            ],
            [
                'id'          => 3,
                'name'        => 'Roles',
                'slug'        => 'roles',
                'http_method' => '',
                'http_path'   => '/auth/roles*',
                'parent_id'   => 1,
                'order'       => 3,
                'created_at'  => $createdAt,
            ],
            [
                'id'          => 4,
                'name'        => 'Permissions',
                'slug'        => 'permissions',
                'http_method' => '',
                'http_path'   => '/auth/permissions*',
                'parent_id'   => 1,
                'order'       => 4,
                'created_at'  => $createdAt,
            ],
            [
                'id'          => 5,
                'name'        => 'Menu',
                'slug'        => 'menu',
                'http_method' => '',
                'http_path'   => '/auth/menu*',
                'parent_id'   => 1,
                'order'       => 5,
                'created_at'  => $createdAt,
            ],
            [
                'id'          => 6,
                'name'        => 'Extension',
                'slug'        => 'extension',
                'http_method' => '',
                'http_path'   => '/auth/extensions*',
                'parent_id'   => 1,
                'order'       => 6,
                'created_at'  => $createdAt,
            ],
        ]);

        //        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id'     => 0,
                'order'         => 1,
                'title'         => 'Index',
                'icon'          => 'feather icon-bar-chart-2',
                'uri'           => '/',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 2,
                'title'         => trans('admin.dashboard.title.list'),
                'icon'          => 'feather icon-bar-chart-2',
                'uri'           => '/user-bet-statistics',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 3,
                'title'         => 'Admin',
                'icon'          => 'feather icon-users',
                'uri'           => '',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 3,
                'order'         => 4,
                'title'         => 'Users',
                'icon'          => '',
                'uri'           => 'auth/users',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 3,
                'order'         => 5,
                'title'         => 'Roles',
                'icon'          => '',
                'uri'           => 'auth/roles',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 3,
                'order'         => 6,
                'title'         => 'Permission',
                'icon'          => '',
                'uri'           => 'auth/permissions',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 3,
                'order'         => 7,
                'title'         => 'Menu',
                'icon'          => '',
                'uri'           => 'auth/menu',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 3,
                'order'         => 8,
                'title'         => 'Extensions',
                'icon'          => '',
                'uri'           => 'auth/extensions',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 9,
                'title'         => trans('admin.users.title.list'),
                'icon'          => 'feather icon-users',
                'uri'           => '/user',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 10,
                'title'         => trans('admin.user_marketing.title.list'),
                'icon'          => 'fa fa-user',
                'uri'           => '/user-marketing',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 11,
                'title'         => trans('admin.bet.title.list'),
                'icon'          => 'fa fa-gamepad',
                'uri'           => '/bet',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 12,
                'title'         => trans('admin.transaction_detail.title.list'),
                'icon'          => 'feather icon-trending-up',
                'uri'           => '/transaction-detail',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 13,
                'title'         => trans('admin.lucky_wheel.title.list'),
                'icon'          => 'feather icon-crosshair',
                'uri'           => '/lucky-wheel',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 14,
                'title'         => trans('admin.ticket_history.title.list'),
                'icon'          => 'feather icon-clock',
                'uri'           => '/ticket-history',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 15,
                'title'         => trans('admin.settings.title.list'),
                'icon'          => 'feather icon-settings',
                'uri'           => '/setting',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 16,
                'title'         => trans('admin.commission.title.list'),
                'icon'          => 'feather icon-dollar-sign',
                'uri'           => '/commission',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 17,
                'title'         => trans('admin.transaction_history.title.list'),
                'icon'          => 'fa fa-random',
                'uri'           => '/transaction-history',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 18,
                'title'         => trans('admin.users.title.user_online'),
                'icon'          => 'fa fa-users',
                'uri'           => '/user-online',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 19,
                'title'         => trans('admin.users.title.user_bet'),
                'icon'          => 'fa fa-btc',
                'uri'           => '/user-bet',
                'created_at'    => $createdAt,
            ],
            [
                'parent_id'     => 0,
                'order'         => 20,
                'title'         => trans('admin.command.title.list'),
                'icon'          => 'fa fa-exchange',
                'uri'           => '/history-command',
                'created_at'    => $createdAt,
            ],
        ]);

        (new Menu())->flushCache();
    }
}

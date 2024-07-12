<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('dashboard');
    $router->resource('user', 'UserController');
    $router->post('/user/{id}/setting', 'UserController@updateSetting');
    $router->post('/user/{id}/level', 'UserController@updateLevel');
    $router->put('/user/{id}/edit-status', 'UserController@editStatus')->name('user.editStatus');

    $router->resource('transaction-detail', 'TransactionDetailController');
    $router->post('/transaction-detail/{id}/edit-status', 'TransactionDetailController@editStatus')->name('transaction-detail.editStatus');

    $router->resource('lucky-wheel', 'LuckyWheelController');

    $router->resource('ticket-history', 'TicketHistoryController');

    $router->resource('setting', 'SettingController');

    $router->match(['put', 'post'], '/upload', 'UploadController@upload')->name('upload');

    $router->resource('commission', 'CommissionController');

    $router->resource('transaction-history', 'TransactionHistoryController');

    $router->get('user-online/render-table', 'RedisController@renderTable')->name('user-online.render-table');
    $router->resource('user-online', 'RedisController');

    $router->get('user-bet/render-table', 'RedisUserBetController@renderTable')->name('user-bet.render-table');
    $router->post('user-bet/create-regulation-command', 'RedisUserBetController@regulation')->name('user-bet.regulation');
    $router->resource('user-bet', 'RedisUserBetController');

    $router->resource('history-command', 'HistoryCommandBetController');

    $router->resource('user-marketing', 'UserMarketingController');

    $router->resource('pool', 'PoolController');
    
    $router->resource('bet', 'BetController');

    $router->resource('user-bet-statistics', 'ReportUserBetController');
});

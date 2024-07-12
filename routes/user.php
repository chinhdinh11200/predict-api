<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register user api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "user" middleware group. Enjoy building your user api!
|
*/

Route::get('/master-data', 'MasterDataController@show')->name('masterData');
Route::post('/upload-image', 'UploadImageController@upload')->name('uploadImage');
Route::get('/zipcode', 'ZipcodeController@index')->name('getZipcode');

Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
    Route::post('/register', 'AuthController@register')->name('register');
    Route::post('/verify-email', 'AuthController@registerVerify')->name('registerVerify');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/logout', 'AuthController@logout')->name('logout');
    Route::post('/forgot-password', 'AuthController@sendForgotPasswordRequest')->name('sendForgotPasswordRequest');
    Route::post('/forgot-password/verify', 'AuthController@verifyForgotPasswordRequest')->name('verifyForgotPasswordRequest');
    Route::get('/me', 'AuthController@currentLoginUser')->name('currentLoginUser');
    Route::post('/me', 'AuthController@updateProfile')->name('updateProfile');
    Route::post('/change-password', 'AuthController@changePassword')->name('changePassword');
    Route::post('/reset-virtual-balance', 'AuthController@resetVirtualBalance')->name('resetVirtualBalance');
    Route::get('/balance', 'AuthController@getMyBalance')->name('getMyBalance');
    Route::post('/upload-avatar', 'AuthController@uploadAvatar')->name('uploadAvatar');
    Route::get('/vip-member', 'AuthController@vipMember')->name('vipMember');
});

Route::group(['as' => 'transactions.', 'prefix' => 'transactions', 'middleware' => 'auth'], function () {
    Route::post('/internal-deposit', 'TransactionController@internalDepositMoney')->name('internalDepositMoney');
    Route::post('/internal-withdraw', 'TransactionController@internalWithdrawMoney')->name('internalWithdrawMoney');
    Route::post('/withdraw', 'TransactionController@withdrawTransaction')->name('withdrawTransaction');
    Route::get('/wallet-deposit', 'TransactionController@getWalletDeposit')->name('walletDeposit');
    Route::post('/wallet-deposit', 'TransactionController@generateWallet')->name('walletDeposit');
    Route::get('/history', 'TransactionController@history')->name('history');
});

Route::group(['as' => 'commissions.', 'prefix' => 'commissions', 'middleware' => 'auth'], function () {
    Route::get('/history', 'HistoryCommissionController@history');
});

Route::group(['as' => 'pools.', 'prefix' => 'pools', 'middleware' => 'auth'], function () {
    Route::get('/history', 'PoolController@history')->name('history');
});

Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {
    Route::get('/', 'SettingController@index')->name('index');
});

Route::group(['as' => 'tickets.', 'prefix' => 'tickets', 'middleware' => 'auth'], function () {
    Route::get('/history', 'TicketController@history')->name('history');
    Route::post('/buy', 'TicketController@buyTicket')->name('buyTicket');
    Route::post('/spin', 'TicketController@spinWheel')->name('spinWheel');
});

Route::group(['as' => 'charts.', 'prefix' => 'charts'], function () {
    Route::get('/', 'ChartController@chart')->name('chart');
    Route::get('/last-result', 'ChartController@lastResult')->name('lastResult');
});

Route::group(['as' => 'bets.', 'prefix' => 'bets', 'middleware' => 'auth'], function () {
    Route::post('/', 'BetController@bet')->name('index');
    Route::get('/history', 'BetController@history')->name('history');
    Route::get('/trade-history', 'BetController@tradeHistory')->name('tradeHistory');
    Route::post('/update-user-balance', 'BetController@updateUserBalance')->name('updateUserBalance');
});

Route::group(['as' => 'dashboard.', 'prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::get('/', 'DashboardController@dashboard')->name('dashboard');
});

Route::group(['as' => 'indicators.', 'prefix' => 'indicators'], function () {
    Route::get('/', 'IndicatorController@index')->name('index');
});

Route::group(['as' => 'trader-sentiments.', 'prefix' => 'trader-sentiments'], function () {
    Route::get('/', 'IndicatorController@getTraderSentiments')->name('index');
});

Route::group(['as' => 'agency.', 'prefix' => 'agency', 'middleware' => 'auth'], function () {
    Route::post('/buy-vip', 'AgencyController@buyVip')->name('buyVip');
    Route::post('/buy-normal', 'AgencyController@buyNormal')->name('buyNormal');
});

Route::group(['as' => 'session.', 'prefix' => 'session', 'middleware' => 'auth'], function () {
    Route::get('/', 'ChartController@getCurrentSession')->name('index');
});

Route::group(['as' => 'statistics.', 'prefix' => 'statistics', 'middleware' => 'auth'], function () {
    Route::get('/', 'StatisticsController@index')->name('index');
});

Route::group(['as' => 'user-settings.', 'prefix' => 'user-settings', 'middleware' => 'auth'], function () {
    Route::get('/', 'UserSettingController@getUserSetting')->name('getUserSetting');
    Route::post('/', 'UserSettingController@updateUserSetting')->name('updateUserSetting');
});

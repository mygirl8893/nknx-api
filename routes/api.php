<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//get all blocks
Route::get('blocks', 'BlockController@showAll');
//get specific block
Route::get('blocks/{block_id}', 'BlockController@show');
//get transactions of specific block
Route::get('blocks/{block_id}/transactions', 'BlockController@showBlockTransactions');

Route::get('checkPort','PortCheckController@checkPort');

//get all transactions
Route::get('transactions', 'TransactionController@showAll');
Route::get('transactions/{tHash}', 'TransactionController@show');

Route::get('walletNames', 'AddressBookController@showWalletNames');
Route::get('walletNames/{walletAddress}', 'AddressBookController@getNameByAddress');


Route::get('payloads/{tId}', 'PayloadController@show');

Route::get('statistics/daily/blocks', 'StatisticController@blocks_daily');
Route::get('statistics/daily/transactions', 'StatisticController@transactions_daily');
Route::get('statistics/daily/transfers', 'StatisticController@transfers_daily');
Route::get('statistics/monthly/blocks', 'StatisticController@blocks_monthly');
Route::get('statistics/monthly/transactions', 'StatisticController@transactions_monthly');
Route::get('statistics/monthly/transfers', 'StatisticController@transfers_monthly');
Route::get('statistics/miners', 'StatisticController@miners_overall');
Route::get('statistics/network', 'StatisticController@network');

//get all outputs
Route::get('outputs', 'OutputController@showAll');

Route::get('addresses', 'AddressController@showAll');
Route::get('addresses/{address}', 'AddressController@show');

Route::get('crawledNodes', 'CrawledNodesController@showAll');

Route::post('auth/register', 'AuthController@register');
Route::get('auth/verify/{token}', 'AuthController@verifyUser');

Route::post('auth/reset/{token}', 'AuthController@setNewPasswordFromToken');
Route::post('auth/reset', 'AuthController@resetPassword');


Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){

  Route::get('auth/user/notifications', 'NotificationsConfigController@showNotificationsConfig');
  Route::put('auth/user/notifications', 'NotificationsConfigController@updateNotificationsConfig');

  Route::get('auth/user', 'AuthController@user');
  Route::post('auth/logout', 'AuthController@logout');
  Route::get('auth/resendVerification', 'AuthController@resendVerification');
  Route::post('auth/changeUser', 'AuthController@changeUser');
  Route::resource('nodes', 'NodeController')->except([
    'create', 'edit', 'update'
  ]);
  Route::delete('nodes', 'NodeController@destroyAll');
  Route::resource('walletAddresses', 'WalletAddressController')->except([
    'create', 'edit', 'update'
  ]);
  Route::get('walletAddresses/{walletAddress}/miningOutputDaily', 'WalletAddressController@getMiningOutputDaily');
  Route::get('walletAddresses/{walletAddress}/miningOutputMonthly', 'WalletAddressController@getMiningOutputMonthly');

});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});

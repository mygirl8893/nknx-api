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
Route::get('blocks/{id}', 'BlockController@show');
//get transactions of specific block
Route::get('blocks/{id}/transactions', 'BlockController@showBlockTransactions');



//get all transactions
Route::get('transactions', 'TransactionController@showAll');
Route::get('transactions/walletNames', 'TransactionController@showWalletNames');
Route::get('transactions/{id}', 'TransactionController@show');


Route::get('payloads/{tId}', 'PayloadController@show');

Route::get('transfers', 'TransferController@showAll');

//get all outputs
Route::get('outputs', 'OutputController@showAll');

Route::get('crawledNodes', 'CrawledNodesController@showAll');

Route::post('auth/register', 'AuthController@register');
Route::get('auth/verify/{token}', 'AuthController@verifyUser');

Route::post('auth/reset/{token}', 'AuthController@setNewPasswordFromToken');
Route::post('auth/reset', 'AuthController@resetPassword');


Route::post('auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');
  Route::post('auth/logout', 'AuthController@logout');
  Route::get('auth/resendVerification', 'AuthController@resendVerification');
  Route::resource('nodes', 'NodeController')->except([
    'create', 'edit', 'update'
  ]);
  Route::delete('nodes', 'NodeController@destroyAll');
  Route::resource('walletAddresses', 'WalletAddressController')->except([
    'create', 'edit', 'update'
  ]);
  Route::get('walletAddresses/{walletAddress}/miningOutput', 'WalletAddressController@getMiningOutput');
  
});
Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//get all blocks
Route::get('blocks', 'BlockController@showAll');
//get specific block
Route::get('blocks/{id}', 'BlockController@show');
//get transactions of specific block
Route::get('blocks/{id}/transactions', 'BlockController@showBlockTransactions');


//get all transactions
Route::get('transactions', 'TransactionController@showAll');

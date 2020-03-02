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

Route::middleware('cors')->get('/saldo', 'ContasController@consultaSaldo');
Route::middleware('cors')->resource('contas', 'ContasController');
Route::middleware('cors')->resource('transacoes', 'TransactionsController');
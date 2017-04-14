<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
 */

Route::group(['prefix' => 'transaction', 'middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'transaction.list', 'uses' => 'TransactionController@index', 'middleware' => 'permission:transaction.list']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'transaction.datagrid.json', 'uses' => 'TransactionController@datagrid', 'middleware' => 'permission:transaction.list']);
});

Route::group(['prefix' => 'qrcode', 'middleware' => 'auth'], function () {
    Route::match(['get', 'post'], 'datagrid.json', ['as' => 'qrcode.datagrid', 'uses' => 'QrCodeController@qrcodes', 'middleware' => 'permission:qrcode.batch.list']);
    Route::get('/', ['as' => 'qrcode.batch.list', 'uses' => 'QrCodeController@index', 'middleware' => 'permission:qrcode.batch.list']);
    Route::get('/export', ['as' => 'qrcode.batch.export', 'uses' => 'QrCodeController@export', 'middleware' => 'permission:qrcode.batch.list']);
    Route::match(['get', 'post'], '/unbind', ['as' => 'qrcode.batch.unbind', 'uses' => 'QrCodeController@unbind', 'middleware' => 'permission:qrcode.batch.add']);
    Route::get('/add', ['as' => 'qrcode.batch.add', 'uses' => 'QrCodeController@add', 'middleware' => 'permission:qrcode.batch.add']);
    Route::post('/make', ['as' => 'qrcode.batch.make', 'uses' => 'QrCodeController@make', 'middleware' => 'permission:qrcode.batch.add']);
    Route::match(['get', 'post'], '/batch/datagrid.json', ['as' => 'qrcode.batch.datagrid.json', 'uses' => 'QrCodeController@datagrid', 'middleware' => 'permission:qrcode.batch.list']);
});

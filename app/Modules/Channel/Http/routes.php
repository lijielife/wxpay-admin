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

Route::group(['prefix' => 'channel', 'middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'channel.list', 'uses' => 'ChannelController@index', 'middleware' => 'permission:channel.list']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'channel.datagrid.json', 'uses' => 'ChannelController@datagrid', 'middleware' => 'permission:channel.list|merchant.add|merchant.edit|transaction.list']);
    Route::get('/add', ['as' => 'channel.add', 'uses' => 'ChannelController@add', 'middleware' => 'permission:channel.add']);
    Route::get('/edit', ['as' => 'channel.edit', 'uses' => 'ChannelController@edit', 'middleware' => 'permission:channel.edit']);
    Route::post('/save', ['as' => 'channel.save', 'uses' => 'ChannelController@save', 'middleware' => 'permission:channel.add|channel.edit|channel.examine|channel.activate']);
    Route::post('/destroy', ['as' => 'channel.destroy', 'uses' => 'ChannelController@destroy', 'middleware' => 'permission:channel.delete']);
    Route::get('/examine', ['as' => 'channel.examine', 'uses' => 'ChannelController@examine', 'middleware' => 'permission:channel.examine']);
    Route::get('/activate', ['as' => 'channel.activate', 'uses' => 'ChannelController@activate', 'middleware' => 'permission:channel.activate']);
    Route::get('/info', ['as' => 'channel.info', 'uses' => 'ChannelController@info', 'middleware' => 'permission:channel.info']);

    Route::group(['prefix' => 'payment'], function () {
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'channel.payment.datagrid.json', 'uses' => 'ChannelController@payments', 'middleware' => 'permission:channel.list|channel.info']);
        Route::get('/add', ['as' => 'channel.payment.add', 'uses' => 'ChannelController@addPayment', 'middleware' => 'permission:channel.payment.add']);
        Route::get('/edit', ['as' => 'channel.payment.edit', 'uses' => 'ChannelController@editPayment', 'middleware' => 'permission:channel.payment.edit']);
        Route::get('/activate', ['as' => 'channel.payment.activate', 'uses' => 'ChannelController@activatePayment', 'middleware' => 'permission:channel.payment.activate']);
        Route::post('/save', ['as' => 'channel.payment.save', 'uses' => 'ChannelController@savePayment', 'middleware' => 'permission:channel.payment.add|channel.payment.edit|channel.payment.activate']);
    });
});

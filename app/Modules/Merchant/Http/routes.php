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

Route::group(['prefix' => 'merchant', 'middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'merchant.list', 'uses' => 'MerchantController@index', 'middleware' => 'permission:merchant.list']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'merchant.datagrid.json', 'uses' => 'MerchantController@datagrid', 'middleware' => 'permission:merchant.list|department.add|department.edit|transaction.list']);
    Route::get('/department.json', ['as' => 'merchant.department.json', 'uses' => 'MerchantController@departments', 'middleware' => 'permission:department.add']);
    Route::get('/add', ['as' => 'merchant.add', 'uses' => 'MerchantController@add', 'middleware' => 'permission:merchant.add']);
    Route::get('/edit', ['as' => 'merchant.edit', 'uses' => 'MerchantController@edit', 'middleware' => 'permission:merchant.edit']);
    Route::post('/save', ['as' => 'merchant.save', 'uses' => 'MerchantController@save', 'middleware' => 'permission:merchant.add|merchant.edit|merchant.examine|merchant.activate']);
    Route::post('/destroy', ['as' => 'merchant.destroy', 'uses' => 'MerchantController@destroy', 'middleware' => 'permission:merchant.delete']);
    Route::get('/examine', ['as' => 'merchant.examine', 'uses' => 'MerchantController@examine', 'middleware' => 'permission:merchant.examine']);
    Route::get('/activate', ['as' => 'merchant.activate', 'uses' => 'MerchantController@activate', 'middleware' => 'permission:merchant.activate']);

    Route::get('/accounts/{merchantId}', ['as' => 'merchant.accounts', 'uses' => 'MerchantController@billingAccounts', 'middleware' => 'permission:merchant.add']);
    Route::get('/info', ['as' => 'merchant.info', 'uses' => 'MerchantController@info', 'middleware' => 'permission:merchant.info']);

    Route::group(['prefix' => 'payment'], function () {
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'merchant.payment.datagrid.json', 'uses' => 'MerchantController@payments', 'middleware' => 'permission:merchant.list|merchant.info']);
        Route::get('/add', ['as' => 'merchant.payment.add', 'uses' => 'MerchantController@addPayment', 'middleware' => 'permission:merchant.payment.add']);
        Route::get('/edit', ['as' => 'merchant.payment.edit', 'uses' => 'MerchantController@editPayment', 'middleware' => 'permission:merchant.payment.edit']);
        Route::post('/save', ['as' => 'merchant.payment.save', 'uses' => 'MerchantController@savePayment', 'middleware' => 'permission:merchant.payment.add|merchant.payment.edit']);
        Route::get('/activate', ['as' => 'merchant.payment.activate', 'uses' => 'MerchantController@activatePayment', 'middleware' => 'permission:merchant.payment.activate']);
    });

    Route::group(['prefix' => 'store', 'middleware' => 'auth'], function () {
        Route::get('/', ['as' => 'store.list', 'uses' => 'MerchantController@index', 'middleware' => 'permission:store.list']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'store.datagrid.json', 'uses' => 'MerchantController@datagrid', 'middleware' => 'permission:store.list']);
        Route::get('/add', ['as' => 'store.add', 'uses' => 'MerchantController@add', 'middleware' => 'permission:store.add']);
        Route::get('/edit', ['as' => 'store.edit', 'uses' => 'MerchantController@edit', 'middleware' => 'permission:store.edit']);
        Route::post('/save', ['as' => 'store.save', 'uses' => 'MerchantController@save', 'middleware' => 'permission:store.add|store.edit']);
        Route::post('/destroy', ['as' => 'store.destroy', 'uses' => 'MerchantController@destroy', 'middleware' => 'permission:store.delete']);
    });

    Route::group(['prefix' => 'department'], function () {
        Route::get('/', ['as' => 'department.list', 'uses' => 'DepartmentController@index', 'middleware' => 'permission:department.list']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'department.datagrid.json', 'uses' => 'DepartmentController@datagrid', 'middleware' => 'permission:department.list']);
        Route::get('/add', ['as' => 'department.add', 'uses' => 'DepartmentController@add', 'middleware' => 'permission:department.add']);
        Route::get('/edit', ['as' => 'department.edit', 'uses' => 'DepartmentController@edit', 'middleware' => 'permission:department.edit']);
        Route::post('/save', ['as' => 'department.save', 'uses' => 'DepartmentController@save', 'middleware' => 'permission:department.add|department.edit']);
        Route::post('/destroy', ['as' => 'department.destroy', 'uses' => 'DepartmentController@destroy', 'middleware' => 'permission:department.delete']);
    });

    Route::group(['prefix' => 'cashier'], function () {
        Route::get('/', ['as' => 'cashier.list', 'uses' => 'CashierController@index', 'middleware' => 'permission:cashier.list']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'cashier.datagrid.json', 'uses' => 'CashierController@datagrid', 'middleware' => 'permission:cashier.list']);
        Route::get('/add', ['as' => 'cashier.add', 'uses' => 'CashierController@add', 'middleware' => 'permission:cashier.add']);
        Route::get('/edit', ['as' => 'cashier.edit', 'uses' => 'CashierController@edit', 'middleware' => 'permission:cashier.edit']);
        Route::post('/save', ['as' => 'cashier.save', 'uses' => 'CashierController@save', 'middleware' => 'permission:cashier.add|cashier.edit']);
        Route::post('/destroy', ['as' => 'cashier.destroy', 'uses' => 'CashierController@destroy', 'middleware' => 'permission:cashier.delete']);
    });
});

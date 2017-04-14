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
Route::get('/rbac/menu/nav.json', ['as' => 'menu.nav.json', 'uses' => 'MenuController@nav', 'middleware' => 'auth']);
Route::match(['get', 'post'], '/rbac/user/resetpwd', ['as' => 'user.resetpwd', 'uses' => 'UserController@resetpwd', 'middleware' => 'auth']);

Route::group(['prefix' => 'rbac', 'middleware' => ['auth', 'role:super.admin']], function () {
    Route::get('/', function () {
        dd('This is the Rbac module index page.');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', ['as' => 'user.list', 'uses' => 'UserController@index']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'user.datagrid.json', 'uses' => 'UserController@getUsersList']);
        Route::get('/add', ['as' => 'user.add', 'uses' => 'UserController@add']);
        Route::get('/edit', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
        Route::post('/save', ['as' => 'user.save', 'uses' => 'UserController@save']);
        Route::post('/destroy', ['as' => 'user.destroy', 'uses' => 'UserController@destroy']);
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', ['as' => 'permission.list', 'uses' => 'PermissionController@index']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'permission.datagrid.json', 'uses' => 'PermissionController@getPermissionsList']);
        Route::get('/add', ['as' => 'permission.add', 'uses' => 'PermissionController@add']);
        Route::get('/edit', ['as' => 'permission.edit', 'uses' => 'PermissionController@edit']);
        Route::post('/save', ['as' => 'permission.save', 'uses' => 'PermissionController@save']);
        Route::post('/destroy', ['as' => 'permission.destroy', 'uses' => 'PermissionController@destroy']);
    });

    Route::group(['prefix' => 'role'], function () {
        Route::get('/', ['as' => 'role.list', 'uses' => 'RoleController@index']);
        Route::match(['get', 'post'], '/menu-permission-tree.json', ['as' => 'menu.permission.tree .json', 'uses' => 'MenuController@getMenuPermissionTree']);
        Route::match(['get', 'post'], '/role-tree.json', ['as' => 'role.tree.json', 'uses' => 'RoleController@getRolesList']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'role.datagrid.json', 'uses' => 'RoleController@getRolesList']);
        Route::get('/add', ['as' => 'role.add', 'uses' => 'RoleController@add']);
        Route::get('/edit', ['as' => 'role.edit', 'uses' => 'RoleController@edit']);
        Route::post('/save', ['as' => 'role.save', 'uses' => 'RoleController@save']);
        Route::post('/destroy', ['as' => 'role.destroy', 'uses' => 'RoleController@destroy']);
    });

    Route::group(['prefix' => 'menu'], function () {
        Route::get('/', ['as' => 'menu.list', 'uses' => 'MenuController@index']);
        Route::match(['get', 'post'], '/datagrid.json', ['as' => 'menu.datagrid.json', 'uses' => 'MenuController@getMenusList']);
        Route::get('/add', ['as' => 'menu.add', 'uses' => 'MenuController@add']);
        Route::get('/edit', ['as' => 'menu.edit', 'uses' => 'MenuController@edit']);
        Route::post('/save', ['as' => 'menu.save', 'uses' => 'MenuController@save']);
        Route::post('/destroy', ['as' => 'menu.destroy', 'uses' => 'MenuController@destroy']);
    });

});

Route::group(['prefix' => 'bank'], function () {
    Route::get('/', ['as' => 'bank.list', 'uses' => 'BankController@index']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'bank.datagrid.json', 'uses' => 'BankController@datagrid']);
    Route::get('/add', ['as' => 'bank.add', 'uses' => 'BankController@add']);
    Route::get('/edit', ['as' => 'bank.edit', 'uses' => 'BankController@edit']);
    Route::post('/save', ['as' => 'bank.save', 'uses' => 'BankController@save']);
    Route::post('/destroy', ['as' => 'bank.destroy', 'uses' => 'BankController@destroy']);
});

Route::group(['prefix' => 'industry'], function () {
    Route::get('/', ['as' => 'industry.list', 'uses' => 'IndustryController@index']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'industry.datagrid.json', 'uses' => 'IndustryController@datagrid']);
    Route::get('/add', ['as' => 'industry.add', 'uses' => 'IndustryController@add']);
    Route::get('/edit', ['as' => 'industry.edit', 'uses' => 'IndustryController@edit']);
    Route::post('/save', ['as' => 'industry.save', 'uses' => 'IndustryController@save']);
    Route::post('/destroy', ['as' => 'industry.destroy', 'uses' => 'IndustryController@destroy']);
});

Route::group(['prefix' => 'region'], function () {
    Route::get('/', ['as' => 'region.list', 'uses' => 'RegionController@index']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'region.datagrid.json', 'uses' => 'RegionController@datagrid']);
    Route::get('/add', ['as' => 'region.add', 'uses' => 'RegionController@add']);
    Route::get('/edit', ['as' => 'region.edit', 'uses' => 'RegionController@edit']);
    Route::post('/save', ['as' => 'region.save', 'uses' => 'RegionController@save']);
    Route::post('/destroy', ['as' => 'region.destroy', 'uses' => 'RegionController@destroy']);
    Route::get('/cascade', ['as' => 'region.area.list', 'uses' => 'RegionController@findByLevel']);
});

Route::group(['prefix' => 'industry'], function () {
    Route::get('/', ['as' => 'industry.list', 'uses' => 'IndustryController@index']);
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'industry.datagrid.json', 'uses' => 'IndustryController@datagrid']);
    Route::get('/add', ['as' => 'industry.add', 'uses' => 'IndustryController@add']);
    Route::get('/edit', ['as' => 'industry.edit', 'uses' => 'IndustryController@edit']);
    Route::post('/save', ['as' => 'industry.save', 'uses' => 'IndustryController@save']);
    Route::post('/destroy', ['as' => 'industry.destroy', 'uses' => 'IndustryController@destroy']);
});

Route::group(['prefix' => 'payment'], function () {
    Route::match(['get', 'post'], '/datagrid.json', ['as' => 'payment.datagrid.json', 'uses' => 'PaymentController@datagrid']);
});

Route::post('/upload', ['as' => 'file.upload', 'uses' => 'FileController@upload']);
Route::get('/download', ['as' => 'file.download', 'uses' => 'FileController@download']);

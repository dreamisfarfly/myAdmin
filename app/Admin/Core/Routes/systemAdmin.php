<?php
use Illuminate\Support\Facades\Route;

/**
 * 系统路由配置
 */

//登录接口
Route::post('login','SysLoginController@login');

//获取用户信息
Route::get('getInfo','SysLoginController@getInfo');

//登录接口
Route::get('getRouters','SysLoginController@getRouters');

/**
 * 系统用户
 */
Route::prefix('/system/user')->group(function(){

    /**
     * 列表
     */
    Route::get('/list', 'SysUserController@list');

});

/**
 * 系统角色
 */
Route::prefix('/system/role')->group(function(){

    /**
     * 列表
     */
    Route::get('/list','SysRoleController@list');

});

/**
 * 数据字典信息
 */
Route::prefix('/system/dict/type')->group(function(){

    /**
     * 列表
     */
    Route::get('/list','SysDictTypeController@list');

    /**
     * 详情
     */
    Route::get('/{id}', 'SysDictTypeController@getInfo')->where('id','[0-9]+');

});

/**
 * 数据字典信息
 */
Route::prefix('/system/dict/data')->group(function(){

    /**
     * 列表
     */
    Route::get('/list','SysDictDataController@list');

    /**
     * 详情
     */
    Route::get('/{dictCode}', 'SysDictDataController@getInfo')->where('dictCode','[0-9]+');

});

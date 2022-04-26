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
     * 添加
     */
    Route::post('','SysDictTypeController@add');

    /**
     * 详情
     */
    Route::get('/{id}', 'SysDictTypeController@getInfo')->where('id','[0-9]+');

    /**
     * 更新
     */
    Route::put('/{id}', 'SysDictTypeController@edit')->where('id','[0-9]+');

    /**
     * 删除
     */
    Route::delete('/', 'SysDictTypeController@remove');

    /**
     * 获取字典选择框列表
     */
    Route::get('/optionselect', 'SysDictTypeController@optionSelect');


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

    /**
     * 根据字典类型查询字典数据信息
     */
    Route::get('/type/{dictType}','SysDictDataController@dictType');

});

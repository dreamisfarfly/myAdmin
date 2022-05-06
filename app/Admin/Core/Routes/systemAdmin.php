<?php
use Illuminate\Support\Facades\Route;

/**
 * 系统路由配置
 */

//登录接口
Route::post('login','SysLoginController@login');

//验证码接口
Route::namespace('Common')->group(function(){
    Route::get('captchaImage','CaptchaController@getCode');
});

//获取用户信息
Route::get('getInfo','SysLoginController@getInfo');

//登录接口
Route::get('getRouters','SysLoginController@getRouters');

//退出登录
Route::post('logout','SysLoginController@logout');

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

/**
 * 菜单信息
 */
Route::prefix('/system/menu')->group(function(){

    /**
     * 列表
     */
    Route::get('/list', 'SysMenuController@list');

    /**
     * 树形选择框
     */
    Route::get('/treeselect', 'SysMenuController@treeSelect');

});

/**
 * 部门管理
 */
Route::prefix('/system/dept')->group(function(){

    /**
     * 列表
     */
    Route::get('/list', 'SysDeptController@list');

    /**
     * 获取部门下拉树列表
     */
    Route::get('/treeselect', 'SysDeptController@treeSelect');

});

/**
 * 岗位
 */
Route::prefix('/system/post')->group(function(){

    /**
     * 列表
     */
    Route::get('/list', 'SysPostController@list');

});

/**
 * 参数配置
 */
Route::prefix('/system/config')->group(function(){

    /**
     * 根据参数键名查询参数值
     */
    Route::get('/configKey/{configKey}','SysConfigController@getConfigKey');

});

Route::namespace('Monitor')->prefix('monitor')->group(function(){

    /**
     * 在线用户
     */
    Route::prefix('online')->group(function(){

        /**
         * 列表
         */
        Route::get('list', 'SysUserOnlineController@list');

        /**
         * 列表
         */
        Route::delete('/{tokenId}', 'SysUserOnlineController@forceLogout');

    });

});

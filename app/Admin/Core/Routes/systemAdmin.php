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

    /**
     * 用户详情
     */
    Route::get('/{userId?}', 'SysUserController@getInfo')
        ->where('userId','^[1-9]\d*$');

    /**
     * 新增用户
     */
    Route::post('/', 'SysUserController@add');

    /**
     * 修改用户
     */
    Route::put('/{userId}', 'SysUserController@edit')
        ->where('userId','^[1-9]\d*$');

    /**
     * 修改用户状态
     */
    Route::put('/changeStatus', 'SysUserController@changeStatus');

    /**
     * 重置用户密码
     */
    Route::put('/resetPwd', 'SysUserController@resetPwd');

    /**
     * 删除用户
     */
    Route::delete('/{ids}','SysUserController@remove')
        ->where('ids','^\d+(,\d+)*$');

});

/**
 * 系统角色
 */
Route::prefix('/system/role')->group(function(){

    /**
     * 列表
     */
    Route::get('/list','SysRoleController@list');

    /**
     * 新增角色
     */
    Route::post('/','SysRoleController@add');

    /**
     * 更新角色
     */
    Route::put('/{roleId}','SysRoleController@edit')
        ->where('roleId','^[1-9]\d*$');

    /**
     * 删除角色
     */
    Route::delete('/{roleIds}','SysRoleController@remove')
        ->where('roleIds','^\d+(,\d+)*$');

    /**
     * 状态修改
     */
    Route::put('/changeStatus','SysRoleController@changeStatus');

    /**
     * 详情
     */
    Route::get('/{roleId}','SysRoleController@getInfo')
        ->where('roleId','^[1-9]\d*$');

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
    Route::get('/{id}', 'SysDictTypeController@getInfo')
        ->where('id','^[1-9]\d*$');

    /**
     * 更新
     */
    Route::put('/{id}', 'SysDictTypeController@edit')
        ->where('id','^[1-9]\d*$');

    /**
     * 删除
     */
    Route::delete('/{ids}', 'SysDictTypeController@remove')
        ->where('ids','^\d+(,\d+)*$');

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
     * 新增
     */
    Route::post('/','SysDictDataController@add');

    /**
     * 更新
     */
    Route::put('/{dictCode}', 'SysDictDataController@edit')
        ->where('dictCode','^[1-9]\d*$');

    /**
     * 删除
     */
    Route::delete('/{ids}','SysDictDataController@remove')
        ->where('ids','^\d+(,\d+)*$');

    /**
     * 详情
     */
    Route::get('/{dictCode}', 'SysDictDataController@getInfo')
        ->where('dictCode','^[1-9]\d*$');

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
     * 新增
     */
    Route::post('/', 'SysMenuController@add');

    /**
     * 更改
     */
    Route::put('/{menuId}', 'SysMenuController@edit')
        ->where('menuId','^[1-9]\d*$');

    /**
     * 删除
     */
    Route::delete('/{menuId}', 'SysMenuController@remove')
        ->where('menuId','^[1-9]\d*$');

    /**
     * 树形选择框
     */
    Route::get('/treeselect', 'SysMenuController@treeSelect');

    /**
     * 加载对应角色菜单列表树
     */
    Route::get('/roleMenuTreeselect/{roleId}', 'SysMenuController@roleMenuTreeSelect')
        ->where('roleId','^[1-9]\d*$');

    /**
     * 详情
     */
    Route::get('/{menuId}','SysMenuController@getInfo')
        ->where('menuId','^[1-9]\d*$');

});

/**
 * 部门管理
 */
Route::prefix('/system/dept')->group(function(){

    /**
     * 添加
     */
    Route::post('/', 'SysDeptController@add');

    /**
     * 列表
     */
    Route::get('/list', 'SysDeptController@list');

    /**
     * 更新
     */
    Route::put('/{deptId}', 'SysDeptController@edit')
        ->where('deptId','^[1-9]\d*$');

    /**
     * 删除
     */
    Route::delete('/{deptId}', 'SysDeptController@remove')
        ->where('deptId','^[1-9]\d*$');;

    /**
     * 详情
     */
    Route::get('/{deptId}', 'SysDeptController@getInfo')
        ->where('deptId','^[1-9]\d*$');

    /**
     * 详情
     */
    Route::get('/list/exclude/{deptId?}', 'SysDeptController@excludeChild')
        ->where('deptId','^[1-9]\d*$');

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

    /**
     * 详情
     */
    Route::get('/{postId}', 'SysPostController@getInfo')
        ->where('postId','^[1-9]\d*$');;

    /**
     * 新增
     */
    Route::post('/', 'SysPostController@add');

    /**
     * 更新
     */
    Route::put('/{postId}', 'SysPostController@edit')
        ->where('postId','^[1-9]\d*$');

    /**
     * 删除
     */
    Route::delete('/{ids}', 'SysPostController@remove')
        ->where('ids','^\d+(,\d+)*$');;


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

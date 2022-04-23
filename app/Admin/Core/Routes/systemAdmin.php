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

<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Security\Authentication;

/**
 * 菜单信息
 *
 * @author zjj
 */
class SysMenuController
{

    /**
     * 获取菜单列表
     */
    public function list()
    {
        Authentication::hasPermit('system:menu:list');
    }

    /**
     * 获取菜单列表
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:menu:query');
    }

    /**
     * 获取菜单下拉树列表
     */
    public function treeSelect()
    {

    }

    /**
     * 加载对应角色菜单列表树
     */
    public function roleMenuTreeSelect()
    {

    }

    /**
     * 新增菜单
     */
    public function add()
    {
        Authentication::hasPermit('system:menu:add');
    }

    /**
     * 修改菜单
     */
    public function edit()
    {
        Authentication::hasPermit('system:menu:edit');
    }

    /**
     * 删除菜单
     */
    public function remove()
    {
        Authentication::hasPermit('system:menu:remove');
    }

}

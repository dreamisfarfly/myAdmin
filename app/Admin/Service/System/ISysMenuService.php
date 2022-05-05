<?php

namespace App\Admin\Service\System;

/**
 * 菜单 业务层
 *
 * @author zjj
 */
interface ISysMenuService
{

    /**
     * 根据用户ID查询菜单树信息
     *
     * @param int $userId 用户ID
     */
    function selectMenuTreeByUserId(int $userId);

    /**
     * 根据用户ID查询权限
     *
     * @param int $userId 用户ID
     * @return mixed 权限列表
     */
    function selectMenuPermsByUserId(int $userId);

}

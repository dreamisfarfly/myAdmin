<?php

namespace App\Admin\Service\System;

/**
 * 角色服务接口
 */
interface ISysRoleService
{

    /**
     * 根据条件分页查询角色数据
     *
     * @return mixed
     */
    public function selectRoleList();

}

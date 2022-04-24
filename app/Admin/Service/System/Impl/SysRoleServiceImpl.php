<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Controllers\Model\SysRole;
use App\Admin\Service\System\ISysRoleService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * 角色服务接口实现
 */
class SysRoleServiceImpl implements ISysRoleService
{

    /**
     * 根据条件分页查询角色数据
     *
     * @return LengthAwarePaginator
     */
    public function selectRoleList(): LengthAwarePaginator
    {
        return SysRole::selectRoleList();
    }
}

<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * 角色表
 *
 * @author zjj
 */
class SysRole extends BaseModel
{

    protected $table = 'sys_role';

    /**
     * 根据条件分页查询角色数据
     *
     * @return LengthAwarePaginator
     */
    static function selectRoleList(): LengthAwarePaginator
    {
        return self::customPagination(self::query());
    }

    /**
     * 批量删除角色信息
     *
     * @param array $roleIds 需要删除的角色ID
     * @return bool 结果
     */
    static function deleteRoleByIds(array $roleIds): bool
    {
        return self::query()
            ->where('role_id', $roleIds)
            ->exists();
    }

}

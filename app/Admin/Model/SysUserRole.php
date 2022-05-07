<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;

/**
 * 用户与角色关联表 数据层
 *
 * @author zjj
 */
class SysUserRole extends BaseModel
{

    protected $table = 'sys_user_role';

    /**
     * 通过角色ID查询角色使用数量
     *
     * @param int $roleId 角色ID
     * @return int 结果
     */
    static function countUserRoleByRoleId(int $roleId): int
    {
        return self::query()
            ->where('role_id', $roleId)
            ->count();
    }

    /**
     * 批量新增用户角色信息
     *
     * @param array $roleList 用户角色列表
     * @return bool 结果
     */
    static function insert(array $roleList): bool
    {
        return self::query()->insert($roleList);
    }

}

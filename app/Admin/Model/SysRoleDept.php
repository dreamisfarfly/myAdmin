<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;

/**
 * 角色与部门关联表 数据层
 *
 * @author zjj
 */
class SysRoleDept extends BaseModel
{

    protected $table = 'sys_user_role';

    /**
     * 批量删除角色部门关联信息
     *
     * @param array $ids 需要删除的数据ID
     * @return mixed 结果
     */
    static function deleteRoleDept(array $ids)
    {
        return self::query()
            ->whereIn('role_id', $ids)
            ->delete();
    }

}

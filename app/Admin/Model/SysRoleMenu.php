<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;

/**
 * 角色与菜单关联表 数据层
 *
 * @author zjj
 */
class SysRoleMenu extends BaseModel
{

    /**
     * 批量删除角色菜单关联信息
     *
     * @param array $ids 需要删除的数据ID
     * @return mixed 结果
     */
    static function deleteRoleMenu(array $ids)
    {
        return self::query()
            ->where('role_id', $ids)
            ->delete();
    }

}

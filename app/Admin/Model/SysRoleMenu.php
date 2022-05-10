<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * 角色与菜单关联表 数据层
 *
 * @author zjj
 */
class SysRoleMenu extends BaseModel
{

    protected $table = 'sys_role_menu';

    /**
     * 批量删除角色菜单关联信息
     *
     * @param array $roleIds 需要删除的数据ID
     * @return mixed 结果
     */
    static function deleteRoleMenu(array $roleIds)
    {
        return self::query()
            ->whereIn('role_id', $roleIds)
            ->delete();
    }

    /**
     * 通过角色ID删除角色和菜单关联
     *
     * @param int $roleId 角色ID
     * @return mixed 结果
     */
    static function deleteRoleMenuByRoleId(int $roleId)
    {
        return self::query()
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * 新增角色菜单信息
     *
     * @param array $sysRoleMenu 角色菜单列表
     * @return bool 结果
     */
    static function insertRoleMenu(array $sysRoleMenu): bool
    {
        return self::query()->insert($sysRoleMenu);
    }

    /**
     * 查询菜单是否使用
     *
     * @param int $menuId 菜单ID
     * @return bool 结果
     */
    static function checkMenuExistRole(int $menuId): bool
    {
        return self::query()->where('menu_id', $menuId)->exists();
    }

}

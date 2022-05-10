<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * 菜单
 *
 * @author zjj
 */
class SysMenu extends BaseModel
{

    protected $table = 'sys_menu';

    /**
     * 查询参数
     */
    protected const SELECT_PARAMS = [
        'menu_id as menuId',
        'parent_id as parentId',
        'menu_name as menuName',
        'path',
        'component',
        'visible',
        'status',
        'perms',
        'is_frame as isFrame',
        'is_cache as isCache',
        'menu_type as menuType',
        'icon',
        'order_num as orderNum',
        'create_time as createTime',
    ];

    /**
     * 根据用户ID查询菜单
     *
     * @return Builder[]|Collection 菜单列表
     */
    public static function selectMenuTreeAll()
    {
        return self::query()
            ->whereIn('menu_type', ['M', 'C'])
            ->where('status', 0)
            ->select(self::SELECT_PARAMS)
            ->distinct()
            ->orderBy('parentId')
            ->orderBy('orderNum')
            ->get();
    }

    /**
     * 根据用户ID查询权限
     *
     * @param int $userId 用户ID
     * @return Builder[]|Collection 权限列表
     */
    public static function selectMenuPermsByUserId(int $userId)
    {
        return self::query()
            ->from('sys_menu as m')
            ->leftJoin('sys_role_menu as rm',function($join){
                $join->on('m.menu_id','=','rm.menu_id');
            })
            ->leftJoin('sys_user_role as ur',function($join){
                $join->on('rm.role_id','=','ur.role_id');
            })
            ->leftJoin('sys_role as r',function($join){
                $join->on('r.role_id','=','ur.role_id');
            })
            ->where('m.status',0)
            ->where('r.status',0)
            ->where('ur.user_id',$userId)
            ->select(['m.perms'])
            ->distinct('m.perms')
            ->get();
    }

    /**
     * @param int $userId
     * @return Builder[]|Collection
     */
    public static function selectMenuTreeByUserId(int $userId)
    {
        return self::query()
            ->from('sys_menu as m')
            ->leftJoin('sys_role_menu as rm',function ($join){
                $join->on('m.menu_id', '=', 'rm.menu_id');
            })
            ->leftJoin('sys_user_role as ur',function($join){
                $join->on('rm.role_id', '=', 'ur.role_id');
            })
            ->leftJoin('sys_role as ro',function($join){
                $join->on('ur.role_id', '=', 'ro.role_id');
            })
            ->leftJoin('sys_user as u',function($join){
                $join->on('ur.user_id', '=', 'u.user_id');
            })
            ->where('u.user_id', $userId)
            ->whereIn('m.menu_type', ['M','C'])
            ->where('m.status', 0)
            ->where('ro.status', 0)
            ->select([
                'm.menu_id as menuId',
                'm.parent_id as parentId',
                'm.menu_name as menuName',
                'm.path',
                'm.component',
                'm.visible',
                'm.status',
                'm.perms',
                'm.is_frame as isFrame',
                'm.is_cache as isCache',
                'm.menu_type as menuType',
                'm.icon',
                'm.order_num as orderNum',
                'm.create_time as createTime'])
            ->distinct('m.menu_id')
            ->orderBy('m.parent_id')
            ->orderBy('m.order_num')
            ->get();
    }

    /**
     * 根据用户查询系统菜单列表
     * @param array $queryParams
     * @return array|Builder[]|Collection
     */
    public static function selectMenuList(array $queryParams)
    {
        return self::query()
            ->from('sys_menu as m')
            ->leftJoin('sys_role_menu as rm',function ($join){
                $join->on('m.menu_id', '=', 'rm.menu_id');
            })
            ->leftJoin('sys_user_role as ur',function($join){
                $join->on('rm.role_id', '=', 'ur.role_id');
            })
            ->leftJoin('sys_role as ro',function($join){
                $join->on('ur.role_id', '=', 'ro.role_id');
            })
            ->when(isset($queryParams['userId']),function($query) use($queryParams){
                $query->where('ur.user_id', $queryParams['userId']);
            })
            ->when(isset($queryParams['menuName']),function($query) use($queryParams){
                $query->where('m.menu_name', 'like', $queryParams['menuName'].'%');
            })
            ->when(isset($queryParams['status']),function($query) use($queryParams){
                $query->where('m.status', $queryParams['status']);
            })
            ->select([
                'm.menu_id as menuId',
                'm.parent_id as parentId',
                'm.menu_name as menuName',
                'm.path',
                'm.component',
                'm.visible',
                'm.status',
                'm.perms',
                'm.is_frame as isFrame',
                'm.is_cache as isCache',
                'm.menu_type as menuType',
                'm.icon',
                'm.order_num as orderNum',
                'm.create_time as createTime'])
            ->distinct('m.menu_id')
            ->orderBy('m.parent_id')
            ->orderBy('m.order_num')
            ->get();
    }

    /**
     * 根据角色ID查询菜单树信息
     *
     * @param int $roleId 角色ID
     * @param bool $menuCheckStrictly 菜单树选择项是否关联显示
     * @return mixed 选中菜单列表
     */
    public static function selectMenuListByRoleIdAssociationShow(int $roleId, bool $menuCheckStrictly)
    {
        return self::query()
            ->from('sys_menu as m')
            ->leftJoin('sys_role_menu as rm',function ($join){
                $join->on('m.menu_id', '=', 'rm.menu_id');
            })
            ->where('rm.role_id', $roleId)
            ->when($menuCheckStrictly,function($query)use($roleId){
                $query->whereNotIn('m.menu_id',function($query)use($roleId){
                    $query->select('m.parent_id')->from('sys_menu as m')
                        ->join('sys_role_menu as rm',function($query)use($roleId){
                            $query->on('m.menu_id','=','rm.menu_id')->where('rm.role_id',$roleId);
                        });
                });
            })
            ->orderBy('m.parent_id')
            ->orderBy('m.order_num')
            ->select(['m.menu_id as menuId'])
            ->get();
    }

    /**
     * 根据菜单ID查询信息
     *
     * @param int $menuId 菜单ID
     * @return Builder|Model|object|null 菜单信息
     */
    public static function selectMenuById(int $menuId)
    {
        return self::query()
            ->where('menu_id', $menuId)
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 校验菜单名称是否唯一
     *
     * @param string $menuName 菜单名称
     * @param int $parentId 父菜单ID
     * @return Builder|Model|object|null 结果
     */
    public static function checkMenuNameUnique(string $menuName, int $parentId)
    {
        return self::query()
            ->where('menu_name',$menuName)
            ->where('parent_id',$parentId)
            ->select(self::SELECT_PARAMS)
            ->first();
    }

    /**
     * 新增菜单信息
     *
     * @param array $sysMenu 菜单信息
     * @return bool 结果
     */
    public static function insertMenu(array $sysMenu): bool
    {
        $sysMenu['createTime'] = date('Y-m-d H:i:s');
        return self::query()->insert(self::uncamelize($sysMenu));
    }

    /**
     * 更改菜单
     *
     * @param int $menuId 菜单编号
     * @param array $sysMenu 菜单信息
     * @return int 结果
     */
    public static function updateMenu(int $menuId, array $sysMenu): int
    {
        return self::query()
            ->where('menu_id', $menuId)
            ->update(self::uncamelize($sysMenu,['menu_id']));
    }

}

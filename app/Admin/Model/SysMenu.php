<?php

namespace App\Admin\Model;

use App\Admin\Core\Model\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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

}

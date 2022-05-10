<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Request\System\SysMenuListRequest;
use App\Admin\Request\System\SysMenuRequest;
use App\Admin\Service\System\Impl\SysMenuServiceImpl;
use App\Admin\Service\System\ISysMenuService;
use Illuminate\Http\JsonResponse;

/**
 * 菜单信息
 *
 * @author zjj
 */
class SysMenuController extends BaseController
{

    /**
     * @var ISysMenuService
     */
    private ISysMenuService $sysMenuService;

    /**
     * @param SysMenuServiceImpl $sysMenuService
     */
    public function __construct(SysMenuServiceImpl $sysMenuService)
    {
        $this->sysMenuService = $sysMenuService;
    }

    /**
     * 获取菜单列表
     */
    public function list(SysMenuListRequest $sysMenuListRequest): JsonResponse
    {
        Authentication::hasPermit('system:menu:list');
        $loginUser = SecurityUtils::getLoginUser();
        $userId = $loginUser['sysUser']['userId'];
        return (new AjaxResult())
            ->success(
                $this->sysMenuService->selectMenuList($sysMenuListRequest->getParamsData(['menuName', 'status']), $userId)
            );
    }

    /**
     * 根据菜单编号获取详细信息
     */
    public function getInfo(int $menuId): JsonResponse
    {
        Authentication::hasPermit('system:menu:query');
        return (new AjaxResult())->success($this->sysMenuService->selectMenuById($menuId));
    }

    /**
     * 获取菜单下拉树列表
     */
    public function treeSelect(): JsonResponse
    {
        $loginUser = SecurityUtils::getLoginUser();
        $userId = $loginUser['sysUser']['userId'];
        $menus = $this->sysMenuService->selectMenuList([], $userId);
        return (new AjaxResult())
            ->success(
                $this->sysMenuService->buildMenuTreeSelect($menus->toArray())
            );
    }

    /**
     * 加载对应角色菜单列表树
     */
    public function roleMenuTreeSelect(int $roleId): JsonResponse
    {
        $loginUser = SecurityUtils::getLoginUser();
        $menus = $this->sysMenuService->selectMenuList([],$loginUser['sysUser']['userId']);
        return (new AjaxResult())
            ->put([
                'checkedKeys' => $this->sysMenuService->selectMenuListByRoleId($roleId),
                'menus' => $this->sysMenuService->buildMenuTreeSelect($menus->toArray())
            ])
            ->success();
    }

    /**
     * 新增菜单
     */
    public function add(SysMenuRequest $sysMenuRequest)
    {
        Authentication::hasPermit('system:menu:add');
        switch ($sysMenuRequest->get('menuType'))
        {
            case 'M': //目录
                if(!$sysMenuRequest->exists('path') && $sysMenuRequest->get('path') != null)
                {
                    return (new AjaxResult())->error(' 路由地址不能为空');
                }
                $sysMenu = $sysMenuRequest->getParamsData(['parentId','menuType','icon','menuName','orderNum','isFrame','path','visible','status']);
                break;
            case 'C': //菜单
                if(!$sysMenuRequest->exists('path') && $sysMenuRequest->get('path') != null)
                {
                    return (new AjaxResult())->error(' 路由地址不能为空');
                }
                $sysMenu = $sysMenuRequest->getParamsData(['parentId','menuType','icon','menuName','orderNum','isFrame','path','component','perms','query','isCache','visible','status']);
                break;
            case 'F': //按钮
                $sysMenu = $sysMenuRequest->getParamsData(['parentId','menuType','menuName','orderNum','perms']);
                break;
            default:
                return (new AjaxResult())->error("新增菜单错误，菜单类型不正确！");
        }
        if(UserConstants::NOT_UNIQUE == $this->sysMenuService->checkMenuNameUnique($sysMenu))
        {
            return (new AjaxResult())->error("新增菜单'" . $sysMenu['menuName'] . "'失败，菜单名称已存在");
        }
        if(UserConstants::YES_FRAME == $sysMenu['isFrame'] && !preg_match('/(http|https):\/\/([\w.]+\/?)\S*/',$sysMenu['path']))
        {
            return (new AjaxResult())->error("新增菜单'" . $sysMenu['menuName'] . "'失败，地址必须以http(s)://开头");
        }
        $sysMenu['createBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysMenuService->insertMenu($sysMenu));
    }

    /**
     * 修改菜单
     */
    public function edit()
    {
        Authentication::hasPermit('system:menu:edit');
    }

    /**
     * 删除菜单
     */
    public function remove()
    {
        Authentication::hasPermit('system:menu:remove');
    }

}

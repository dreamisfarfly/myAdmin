<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\TokenService;
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
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @var ISysMenuService
     */
    private ISysMenuService $sysMenuService;

    /**
     * @param TokenService $tokenService
     * @param SysMenuServiceImpl $sysMenuService
     */
    public function __construct(TokenService $tokenService, SysMenuServiceImpl $sysMenuService)
    {
        $this->tokenService = $tokenService;
        $this->sysMenuService = $sysMenuService;
    }

    /**
     * 获取菜单列表
     */
    public function list(SysMenuRequest $sysMenu): JsonResponse
    {
        Authentication::hasPermit('system:menu:list');
        $loginUser = $this->tokenService->getLoginUser();
        $userId = $loginUser['sysUser']['userId'];
        return (new AjaxResult())
            ->success(
                $this->sysMenuService->selectMenuList($sysMenu->getParamsData([]), $userId)
            );
    }

    /**
     * 获取菜单列表
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:menu:query');
    }

    /**
     * 获取菜单下拉树列表
     */
    public function treeSelect(SysMenuRequest $menuRequest): JsonResponse
    {
        $loginUser = $this->tokenService->getLoginUser();
        $userId = $loginUser['sysUser']['userId'];
        $menus = $this->sysMenuService->selectMenuList($menuRequest->getParamsData([]), $userId);
        return (new AjaxResult())
            ->success(
                $this->sysMenuService->buildMenuTreeSelect($menus->toArray())
            );
    }

    /**
     * 加载对应角色菜单列表树
     */
    public function roleMenuTreeSelect()
    {

    }

    /**
     * 新增菜单
     */
    public function add()
    {
        Authentication::hasPermit('system:menu:add');
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

<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Security\Authentication;
use App\Admin\Service\System\Impl\SysRoleServiceImpl;
use App\Admin\Service\System\ISysRoleService;
use Illuminate\Http\JsonResponse;

/**
 * 角色信息
 *
 * @author zjj
 */
class SysRoleController extends BaseController
{

    /**
     * @var ISysRoleService
     */
    private ISysRoleService $sysRoleService;

    /**
     * @param SysRoleServiceImpl $sysRoleService
     */
    public function __construct(SysRoleServiceImpl $sysRoleService)
    {
        $this->sysRoleService = $sysRoleService;
    }

    /**
     * 角色列表
     */
    public function list(): JsonResponse
    {
        Authentication::hasPermit('system:role:list');
        return $this->getDataTable($this->sysRoleService->selectRoleList());
    }

    /**
     * 根据角色编号获取详细信息
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:role:query');
    }

    /**
     * 新增角色
     */
    public function add()
    {
        Authentication::hasPermit('system:role:add');
    }

    /**
     * 修改保存角色
     */
    public function edit()
    {
        Authentication::hasPermit('system:role:edit');
    }

    /**
     * 修改保存数据权限
     */
    public function dataScope()
    {
        Authentication::hasPermit('system:role:edit');
    }

    /**
     * 状态修改
     */
    public function changeStatus()
    {
        Authentication::hasPermit('system:role:edit');
    }

    /**
     * 删除角色
     */
    public function remove()
    {
        Authentication::hasPermit('system:role:remove');
    }

    /**
     * 获取角色选择框列表
     */
    public function optionSelect()
    {
        Authentication::hasPermit('system:role:query');
    }

}

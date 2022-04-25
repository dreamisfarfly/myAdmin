<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Security\Authentication;
use App\Admin\Service\System\Impl\SysUserServiceImpl;
use App\Admin\Service\System\ISysUserService;
use Illuminate\Http\JsonResponse;

/**
 * 用户信息
 *
 * @author zjj
 */
class SysUserController extends BaseController
{

    /**
     * @var ISysUserService
     */
    private ISysUserService $sysUserService;

    /**
     * @param SysUserServiceImpl $sysUserService
     */
    public function __construct(SysUserServiceImpl $sysUserService)
    {
        $this->sysUserService = $sysUserService;
    }

    /**
     * 获取用户列表
     */
    public function list(): JsonResponse
    {
        Authentication::hasPermit('system:user:list');
        return $this->getDataTable($this->sysUserService->selectUserList());
    }

    /**
     * 根据用户编号获取详细信息
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:user:query');
    }

    /**
     * 新增用户
     */
    public function add()
    {
        Authentication::hasPermit('system:user:add');
    }

    /**
     * 修改用户
     */
    public function edit()
    {
        Authentication::hasPermit('system:user:edit');
    }

    /**
     * 删除用户
     */
    public function remove()
    {
        Authentication::hasPermit('system:user:remove');
    }

    /**
     * 重置密码
     */
    public function resetPwd()
    {
        Authentication::hasPermit('system:user:resetPwd');
    }

    /**
     * 状态修改
     */
    public function changeStatus()
    {
        Authentication::hasPermit('system:user:edit');
    }

}

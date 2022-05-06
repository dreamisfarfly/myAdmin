<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Model\SysRole;
use App\Admin\Service\System\Impl\SysPostServiceImpl;
use App\Admin\Service\System\Impl\SysRoleServiceImpl;
use App\Admin\Service\System\Impl\SysUserServiceImpl;
use App\Admin\Service\System\ISysPostService;
use App\Admin\Service\System\ISysRoleService;
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
     * @var ISysRoleService
     */
    private ISysRoleService $sysRoleService;

    /**
     * @var ISysPostService
     */
    private ISysPostService $sysPostService;

    /**
     * @param SysUserServiceImpl $sysUserService
     * @param SysPostServiceImpl $sysPostService
     * @param SysRoleServiceImpl $sysRoleServiceImpl
     */
    public function __construct(SysUserServiceImpl $sysUserService, SysPostServiceImpl $sysPostService,
                                SysRoleServiceImpl $sysRoleServiceImpl)
    {
        $this->sysUserService = $sysUserService;
        $this->sysRoleService = $sysRoleServiceImpl;
        $this->sysPostService = $sysPostService;
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
    public function getInfo(int $userId = null): JsonResponse
    {
        Authentication::hasPermit('system:user:query');
        $rolesList = $this->sysRoleService->selectRoleAll();
        $ajax = new AjaxResult();
        if(SecurityUtils::isAdmin($userId))
        {
            $ajax->put([
                'roles' => $rolesList
            ]);
        }else
        {
            $rolesTemp = [];
            foreach ($rolesList as $item)
            {
                if(!SysRole::isAdmin($item['roleId']))
                {
                    array_push($rolesTemp, $item);
                }
            }
            $ajax->put([
                'roles' => $rolesTemp
            ]);
        }
        $ajax->put(['posts'=>$this->sysPostService->selectPostAll()]);
        if($userId != null){
            $ajax->put([
                AjaxResult::DATA_TAG => $this->sysUserService->selectUserById($userId),
                'postIds' => $this->sysPostService->selectPostListByUserId($userId),
                'roleIds' => $this->sysRoleService->selectRoleListByUserId($userId)
            ]);
        }
        return $ajax->success();
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

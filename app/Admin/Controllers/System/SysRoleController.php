<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Security\TokenService;
use App\Admin\Request\System\Ids;
use App\Admin\Request\System\SysRoleChangeStatusRequest;
use App\Admin\Request\System\SysRoleListRequest;
use App\Admin\Request\System\SysRoleRequest;
use App\Admin\Service\System\Impl\SysPermissionServiceImpl;
use App\Admin\Service\System\Impl\SysRoleServiceImpl;
use App\Admin\Service\System\Impl\SysUserServiceImpl;
use App\Admin\Service\System\ISysPermissionService;
use App\Admin\Service\System\ISysRoleService;
use App\Admin\Service\System\ISysUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @var ISysPermissionService
     */
    private ISysPermissionService $sysPermissionService;

    /**
     * @var ISysUserService
     */
    private ISysUserService $sysUserService;

    /**
     * @param SysRoleServiceImpl $sysRoleService
     * @param TokenService $tokenService
     * @param SysPermissionServiceImpl $sysPermissionService
     * @param SysUserServiceImpl $sysUserService
     */
    public function __construct(SysRoleServiceImpl $sysRoleService, TokenService $tokenService,
                                SysPermissionServiceImpl $sysPermissionService, SysUserServiceImpl $sysUserService)
    {
        $this->sysRoleService = $sysRoleService;
        $this->tokenService = $tokenService;
        $this->sysPermissionService = $sysPermissionService;
        $this->sysUserService = $sysUserService;
    }

    /**
     * 角色列表
     *
     * @PreAuthorize(hasPermi = "system:role:list")
     */
    public function list(SysRoleListRequest $sysRoleListRequest): JsonResponse
    {
        return $this->getDataTable(
            $this->sysRoleService->selectRoleList(
                $sysRoleListRequest->getParamsData(['roleName','roleKey','status','beginTime','endTime'])
            )
        );
    }

    /**
     * 根据角色编号获取详细信息
     *
     * @PreAuthorize(hasPermi = "system:role:query")
     */
    public function getInfo(int $roleId): JsonResponse
    {
        return (new AjaxResult())
            ->success(
                $this->sysRoleService->selectRoleById($roleId)
            );
    }

    /**
     * 新增角色
     * @ForbidSubmit
     * @PreAuthorize(hasPermi = "system:role:add")
     * @Log(title = "角色管理", businessType = BusinessType.INSERT)
     */
    public function add(SysRoleRequest $sysRoleRequest): JsonResponse
    {
        $sysRole = $sysRoleRequest->getParamsData(['deptCheckStrictly', 'deptIds', 'menuCheckStrictly', 'menuIds', 'remark', 'roleKey', 'roleName', 'roleSort', 'status']);
        if(UserConstants::NOT_UNIQUE == $this->sysRoleService->checkAssignUnique(['roleName'=>$sysRole['roleName']]))
        {
            return (new AjaxResult())->error("新增角色'".$sysRole['roleName']."'失败，角色名称已存在");
        }
        if(UserConstants::NOT_UNIQUE == $this->sysRoleService->checkAssignUnique(['roleKey'=>$sysRole['roleKey']]))
        {
            return (new AjaxResult())->error("新增角色'".$sysRole['roleName']."'失败，角色权限已存在");
        }
        $sysRole['deptCheckStrictly'] == 'true' ? $sysRole['deptCheckStrictly'] = 1:$sysRole['deptCheckStrictly'] = 0;
        $sysRole['menuCheckStrictly'] == 'true' ? $sysRole['menuCheckStrictly'] = 1:$sysRole['menuCheckStrictly'] = 0;
        $sysRole['createBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysRoleService->insertRole($sysRole));
    }

    /**
     * 修改保存角色
     * @ForbidSubmit
     * @PreAuthorize(hasPermi = "system:role:edit")
     * @Log(title = "角色管理", businessType = BusinessType.UPDATE)
     */
    public function edit(int $roleId, SysRoleRequest $sysRoleRequest): JsonResponse
    {
        $sysRole = $sysRoleRequest->getParamsData(['deptCheckStrictly', 'deptIds', 'menuCheckStrictly', 'menuIds', 'remark', 'roleKey', 'roleName', 'roleSort', 'status']);
        if(UserConstants::NOT_UNIQUE == $this->sysRoleService->checkAssignUnique(['roleName'=>$sysRole['roleName']],$roleId))
        {
            return (new AjaxResult())->error("更新角色'".$sysRole['roleName']."'失败，角色名称已存在");
        }
        if(UserConstants::NOT_UNIQUE == $this->sysRoleService->checkAssignUnique(['roleKey'=>$sysRole['roleKey']],$roleId))
        {
            return (new AjaxResult())->error("更新角色'".$sysRole['roleName']."'失败，角色权限已存在");
        }
        $sysRole['deptCheckStrictly'] == 'true' ? $sysRole['deptCheckStrictly'] = 1:$sysRole['deptCheckStrictly'] = 0;
        $sysRole['menuCheckStrictly'] == 'true' ? $sysRole['menuCheckStrictly'] = 1:$sysRole['menuCheckStrictly'] = 0;
        $sysRole['updateBy'] = SecurityUtils::getUsername();

        if($this->sysRoleService->updateRole($roleId, $sysRole) > 0)
        {
            // 更新缓存用户权限
            $loginUser = SecurityUtils::getLoginUser();
            if($loginUser != null && !SecurityUtils::isAdmin($loginUser['sysUser']['userId']))
            {
                $loginUser['permissions'] = $this->sysPermissionService->getMenuPermission($loginUser['sysUser']);
                $loginUser['sysUser'] = $this->sysUserService->selectUserByUserName(['userName'=>$loginUser['sysUser']['userName']]);
                $this->tokenService->setLoginUser($loginUser);
            }
            return (new AjaxResult())->success();
        }
        return (new AjaxResult())->error("修改角色'" . $sysRole['roleName'] . "'失败，请联系管理员");
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
     * @PreAuthorize(hasPermi = "system:role:edit")
     * @Log(title = "角色管理", businessType = BusinessType.UPDATE)
     * @throws ParametersException
     */
    public function changeStatus(SysRoleChangeStatusRequest $sysRoleChangeStatusRequest): JsonResponse
    {
        $sysRole = $sysRoleChangeStatusRequest->getParamsData(['roleId', 'status']);
        $this->sysRoleService->checkRoleAllowed($sysRole['roleId']);
        $loginUser = $this->tokenService->getLoginUser();
        $sysRole['updateBy'] = $loginUser['sysUser']['userName'];
        return $this->toAjax($this->sysRoleService->updateRoleStatus($sysRole['roleId'],$sysRole));
    }

    /**
     * 删除角色
     * @PreAuthorize(hasPermi = "system:role:remove")
     * @Log(title = "角色管理", businessType = BusinessType.DELETE)
     * @throws ParametersException
     */
    public function remove(string $roleIds): JsonResponse
    {
        $ids = explode(',', $roleIds);
        return $this->toAjax($this->sysRoleService->deleteRoleByIds($ids));
    }

    /**
     * 获取角色选择框列表
     * @PreAuthorize(hasPermi = "system:role:query")
     */
    public function optionSelect()
    {

    }

}

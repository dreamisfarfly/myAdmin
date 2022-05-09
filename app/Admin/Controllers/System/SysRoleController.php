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
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @param SysRoleServiceImpl $sysRoleService
     * @param TokenService $tokenService
     */
    public function __construct(SysRoleServiceImpl $sysRoleService, TokenService $tokenService)
    {
        $this->sysRoleService = $sysRoleService;
        $this->tokenService = $tokenService;
    }

    /**
     * 角色列表
     */
    public function list(SysRoleListRequest $sysRoleListRequest): JsonResponse
    {
        Authentication::hasPermit('system:role:list');
        return $this->getDataTable(
            $this->sysRoleService->selectRoleList(
                $sysRoleListRequest->getParamsData(['roleName','roleKey','status','beginTime','endTime'])
            )
        );
    }

    /**
     * 根据角色编号获取详细信息
     */
    public function getInfo(int $roleId): JsonResponse
    {
        Authentication::hasPermit('system:role:query');
        return (new AjaxResult())
            ->success(
                $this->sysRoleService->selectRoleById($roleId)
            );
    }

    /**
     * 新增角色
     */
    public function add(SysRoleRequest $sysRoleRequest): JsonResponse
    {
        Authentication::hasPermit('system:role:add');
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
     */
    public function edit(int $roleId, SysRoleRequest $sysRoleRequest)
    {
        Authentication::hasPermit('system:role:edit');
        $sysRole = $sysRoleRequest->getParamsData(['deptCheckStrictly', 'deptIds', 'menuCheckStrictly', 'menuIds', 'remark', 'roleKey', 'roleName', 'roleSort', 'status']);

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
     * @throws ParametersException
     */
    public function changeStatus(SysRoleChangeStatusRequest $sysRoleChangeStatusRequest): JsonResponse
    {
        Authentication::hasPermit('system:role:edit');
        $sysRole = $sysRoleChangeStatusRequest->getParamsData(['roleId', 'status']);
        $this->sysRoleService->checkRoleAllowed($sysRole['roleId']);
        $loginUser = $this->tokenService->getLoginUser();
        $sysRole['updateBy'] = $loginUser['sysUser']['userName'];
        return $this->toAjax($this->sysRoleService->updateRoleStatus($sysRole['roleId'],$sysRole));
    }

    /**
     * 删除角色
     * @throws ParametersException
     */
    public function remove(Ids $ids): JsonResponse
    {
        Authentication::hasPermit('system:role:remove');
        return $this->toAjax($this->sysRoleService->deleteRoleByIds($ids->getParamsData(['ids'])));
    }

    /**
     * 获取角色选择框列表
     */
    public function optionSelect()
    {
        Authentication::hasPermit('system:role:query');
    }

}

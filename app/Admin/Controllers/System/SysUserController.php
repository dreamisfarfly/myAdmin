<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Exports\SysUserExport;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Security\TokenService;
use App\Admin\Model\SysRole;
use App\Admin\Model\SysUser;
use App\Admin\Request\System\EditSysUserRequest;
use App\Admin\Request\System\ResetSysUserPwdRequest;
use App\Admin\Request\System\SysUserChangeStatusRequest;
use App\Admin\Request\System\SysUserListRequest;
use App\Admin\Request\System\SysUserRequest;
use App\Admin\Service\System\Impl\SysPostServiceImpl;
use App\Admin\Service\System\Impl\SysRoleServiceImpl;
use App\Admin\Service\System\Impl\SysUserServiceImpl;
use App\Admin\Service\System\ISysPostService;
use App\Admin\Service\System\ISysRoleService;
use App\Admin\Service\System\ISysUserService;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @param SysUserServiceImpl $sysUserService
     * @param SysPostServiceImpl $sysPostService
     * @param SysRoleServiceImpl $sysRoleServiceImpl
     * @param TokenService $tokenService
     */
    public function __construct(SysUserServiceImpl $sysUserService, SysPostServiceImpl $sysPostService,
                                SysRoleServiceImpl $sysRoleServiceImpl, TokenService $tokenService)
    {
        $this->sysUserService = $sysUserService;
        $this->sysRoleService = $sysRoleServiceImpl;
        $this->sysPostService = $sysPostService;
        $this->tokenService = $tokenService;
    }

    /**
     * 获取用户列表
     * @PreAuthorize(hasPermi = "system:user:list")
     */
    public function list(SysUserListRequest $sysUserListRequest): JsonResponse
    {
        return $this->getDataTable(
            $this->sysUserService->selectUserList(
                $sysUserListRequest->getParamsData([
                    'userName',
                    'phonenumber',
                    'status',
                    'deptId',
                    'beginTime',
                    'endTime'
                ])
            )
        );
    }

    /**
     * 根据用户编号获取详细信息
     * @PreAuthorize(hasPermi = "system:user:query")
     */
    public function getInfo(int $userId = null): JsonResponse
    {
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
     *
     * @ForbidSubmit
     * @PreAuthorize(hasPermi = "system:user:add")
     * @Log(title = "用户管理", businessType = BusinessType.INSERT)
     */
    public function add(SysUserRequest $sysUserRequest): JsonResponse
    {
        $sysUser = $sysUserRequest->getParamsData(['deptId', 'email', 'nickName', 'password', 'phonenumber', 'postIds', 'remark', 'roleIds', 'sex', 'status', 'userName']);
        if(UserConstants::NOT_UNIQUE == $this->sysUserService->checkUserNameUnique($sysUser['userName']))
        {
            return (new AjaxResult())->error('新增用户'.$sysUser['userName'].'失败，登录账号已存在');
        }
        if(isset($sysUser['phonenumber']) &&
            UserConstants::NOT_UNIQUE == $this->sysUserService->checkAssignUnique(['phonenumber'=>$sysUser['phonenumber']]))
        {
            return (new AjaxResult())->error('新增用户'.$sysUser['userName'].'失败，手机号码已存在');
        }
        if(isset($sysUser['email']) &&
            UserConstants::NOT_UNIQUE == $this->sysUserService->checkAssignUnique(['email'=>$sysUser['email']]))
        {
            return (new AjaxResult())->error('新增用户'.$sysUser['userName'].'失败，邮箱账号已存在');
        }
        $sysUser['createBy'] = SecurityUtils::getUsername();
        $sysUser['password'] = SysUser::getPassword($sysUser['password']);
        return $this->toAjax($this->sysUserService->insertUser($sysUser));
    }

    /**
     * 修改用户
     * @ForbidSubmit
     * @Log(title = "用户管理", businessType = BusinessType.UPDATE)
     * @PreAuthorize(hasPermi = "system:user:edit")
     * @throws ParametersException
     */
    public function edit(int $userId, EditSysUserRequest $editSysUserRequest): JsonResponse
    {
        $this->sysUserService->checkUserAllowed($userId);
        $userInfo = $this->sysUserService->selectUserById($userId);
        null != $userInfo?$userName=$userInfo['userName']:$userName='未知用户';
        $sysUser = $editSysUserRequest->getParamsData(['deptId', 'email', 'nickName', 'phonenumber', 'postIds', 'remark', 'roleIds', 'sex', 'status']);
        if(isset($sysUser['phonenumber']) &&
            UserConstants::NOT_UNIQUE == $this->sysUserService->checkAssignUnique(['phonenumber'=>$sysUser['phonenumber']], $userId))
        {
            return (new AjaxResult())->error('修改用户'.$userName.'失败，手机号码已存在');
        }
        if(isset($sysUser['email']) &&
            UserConstants::NOT_UNIQUE == $this->sysUserService->checkAssignUnique(['email'=>$sysUser['email']], $userId))
        {
            return (new AjaxResult())->error('修改用户'.$userName.'失败，邮箱账号已存在');
        }
        $sysUser['updateBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysUserService->updateUser($userId, $sysUser));
    }

    /**
     * 删除用户
     * @PreAuthorize(hasPermi = "system:user:remove")
     * @Log(title = "用户管理", businessType = BusinessType.DELETE)
     * @throws ParametersException
     */
    public function remove(string $ids): JsonResponse
    {
        $ids = explode(',', $ids);
        return $this->toAjax($this->sysUserService->deleteUserByIds($ids));
    }

    /**
     * 重置密码
     * @PreAuthorize(hasPermi = "system:user:resetPwd")
     * @Log(title = "用户管理", businessType = BusinessType.UPDATE)
     * @throws ParametersException
     */
    public function resetPwd(ResetSysUserPwdRequest $resetSysUserPwdRequest): JsonResponse
    {
        $sysUser = $resetSysUserPwdRequest->getParamsData(['userId', 'password']);
        $this->sysUserService->checkUserAllowed($sysUser['userId']);
        $sysUser['updateBy'] = SecurityUtils::getUsername();
        $sysUser['password'] = SysUser::getPassword($sysUser['password']);
        return $this->toAjax($this->sysUserService->resetPwd($sysUser['userId'],$sysUser));
    }

    /**
     * 状态修改
     * @PreAuthorize(hasPermi = "system:user:edit")
     * @Log(title = "用户管理", businessType = BusinessType.UPDATE)
     * @throws ParametersException
     */
    public function changeStatus(SysUserChangeStatusRequest $sysUserChangeStatusRequest): JsonResponse
    {
        $sysUser = $sysUserChangeStatusRequest->getParamsData(['userId', 'status']);
        $this->sysUserService->checkUserAllowed($sysUser['userId']);
        $sysUser['updateBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysUserService->updateUserStatus($sysUser['userId'], $sysUser));
    }

    /**
     * 导出
     * @PreAuthorize(hasPermi = "system:user:export")
     * @Log(title = "用户管理", businessType = BusinessType.EXPORT)
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new SysUserExport(), '系统管理用户.xlsx');
    }

}

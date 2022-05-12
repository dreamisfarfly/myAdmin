<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Core\Security\TokenService;
use App\Admin\Model\SysUser;
use App\Admin\Request\System\EditPwdRequest;
use App\Admin\Request\System\UpdateProfileRequest;
use App\Admin\Service\System\Impl\SysUserServiceImpl;
use App\Admin\Service\System\ISysUserService;
use Illuminate\Http\JsonResponse;

/**
 * 个人信息 业务处理
 *
 * @author zjj
 */
class SysProfileController extends BaseController
{

    /**
     * @var ISysUserService
     */
    private ISysUserService $sysUserService;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @param SysUserServiceImpl $sysUserService
     * @param TokenService $tokenService
     */
    public function __construct(SysUserServiceImpl $sysUserService, TokenService $tokenService)
    {
        $this->sysUserService = $sysUserService;
        $this->tokenService = $tokenService;
    }

    /**
     * 个人信息
     */
    public function profile(): JsonResponse
    {
        $loginUser = SecurityUtils::getLoginUser();
        $user = $loginUser['sysUser'];
        $ajax = new AjaxResult();
        $ajax->put([
            'roleGroup' => $this->sysUserService->selectUserRoleGroup($user['userName']),
            'postGroup' => $this->sysUserService->selectUserPostGroup($user['userName'])
        ]);
        return $ajax->success($user);
    }

    /**
     * 修改用户
     */
    public function updateProfile(UpdateProfileRequest $updateProfileRequest): JsonResponse
    {
        $loginUser = SecurityUtils::getLoginUser();
        $userId = $loginUser['sysUser']['userId'];
        $profile = $updateProfileRequest->getParamsData(['nickName','phonenumber','email','sex']);
        if(isset($profile['phonenumber']) &&
            UserConstants::NOT_UNIQUE == $this->sysUserService->checkAssignUnique(['phonenumber'=>$profile['phonenumber']], $userId))
        {
            return (new AjaxResult())->error('修改用户'.SecurityUtils::getUsername().'失败，手机号码已存在');
        }
        if(isset($profile['email']) &&
            UserConstants::NOT_UNIQUE == $this->sysUserService->checkAssignUnique(['email'=>$profile['email']], $userId))
        {
            return (new AjaxResult())->error('修改用户'.SecurityUtils::getUsername().'失败，邮箱账号已存在');
        }
        if($this->sysUserService->updateUserProfile($userId,$profile))
        {
            // 更新缓存用户信息
            $loginUser['sysUser']['nickName'] = $profile['nickName'];
            $loginUser['sysUser']['phonenumber'] = $profile['phonenumber'];
            $loginUser['sysUser']['email'] = $profile['email'];
            $loginUser['sysUser']['sex'] = $profile['sex'];
            $this->tokenService->setLoginUser($loginUser);
            return (new AjaxResult())->success();
        }
        return (new AjaxResult())->error('修改个人信息异常，请联系管理员');
    }

    /**
     * 重置密码
     */
    public function updatePwd(EditPwdRequest $editPwdRequest): JsonResponse
    {
        $editPwd = $editPwdRequest->getParamsData(['oldPassword','newPassword']);
        $loginUser = SecurityUtils::getLoginUser();
        $password = SysUser::getPassword($editPwd['oldPassword']);
        if($password != $loginUser['sysUser']['password'])
        {
            return (new AjaxResult())->error('修改密码失败，旧密码错误');
        }
        if($editPwd['oldPassword'] == $editPwd['newPassword'])
        {
            return (new AjaxResult())->error('新密码不能与旧密码相同');
        }
        $newPassword = SysUser::getPassword($editPwd['newPassword']);
        if($this->sysUserService
            ->updateUserProfile($loginUser['sysUser']['userId'],['password'=>$newPassword]))
        {
            $loginUser['sysUser']['password'] = $newPassword;
            $this->tokenService->setLoginUser($loginUser);
            return (new AjaxResult())->success();
        }
        return (new AjaxResult())->error('修改密码异常，请联系管理员');
    }

    /**
     * 头像上传
     */
    public function avatar()
    {

    }

}

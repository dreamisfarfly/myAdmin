<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\TokenService;
use App\Admin\Request\System\LoginBody;
use App\Admin\Service\System\Impl\SysLoginServiceImpl;
use App\Admin\Service\System\Impl\SysMenuServiceImpl;
use App\Admin\Service\System\Impl\SysPermissionServiceImpl;
use App\Admin\Service\System\ISysLoginService;
use App\Admin\Service\System\ISysMenuService;
use App\Admin\Service\System\ISysPermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 登录验证
 *
 * @author zjj
 */
class SysLoginController extends BaseController
{

    /**
     * @var ISysLoginService|SysLoginServiceImpl
     */
    private ISysLoginService $sysLoginService;

    /**
     * @var TokenService
     */
    private TokenService $tokenService;

    /**
     * @var ISysMenuService
     */
    private ISysMenuService $sysMenuService;

    /**
     * @var ISysPermissionService|SysPermissionServiceImpl
     */
    private ISysPermissionService $sysPermissionService;

    /**
     * @param SysLoginServiceImpl $sysLoginService
     * @param TokenService $tokenService
     * @param SysMenuServiceImpl $sysMenuService
     * @param SysPermissionServiceImpl $sysPermissionService
     */
    public function __construct(SysLoginServiceImpl $sysLoginService, TokenService $tokenService,
                                SysMenuServiceImpl $sysMenuService, SysPermissionServiceImpl $sysPermissionService)
    {
        $this->sysLoginService = $sysLoginService;
        $this->tokenService = $tokenService;
        $this->sysMenuService = $sysMenuService;
        $this->sysPermissionService = $sysPermissionService;
    }

    /**
     * 登录方法
     *
     * @param LoginBody $loginBody
     * @return JsonResponse
     * @throws ParametersException
     */
    public function login(LoginBody $loginBody): JsonResponse
    {
        request()->attributes->set('userName',$loginBody->get('username'));
        $token = $this->sysLoginService->login(
            $loginBody->get('username'),
            $loginBody->get('password'),
            $loginBody->get('code'),
            $loginBody->get('uuid')
        );
        return (new AjaxResult())->put(['token'=>$token])->msg('登录成功');
    }

    /**
     * 获取用户信息
     */
    public function getInfo(): JsonResponse
    {
        $loginUser = $this->tokenService->getLoginUser();
        // 角色用户信息集合
        $userInfo =  $loginUser['sysUser'];
        // 角色集合
        $roles = $this->sysPermissionService->getRolePermission($userInfo);
        // 权限集合
        $permissions = $this->sysPermissionService->getMenuPermission($userInfo);
        return (new AjaxResult())
            ->put([
                'user' => $userInfo,
                'roles' => $roles,
                'permissions' => $permissions
            ])
            ->success();
    }

    /**
     * 获取路由信息
     */
    public function getRouters(): JsonResponse
    {
        $loginUser = $this->tokenService->getLoginUser();
        $userInfo = $loginUser['sysUser'];
        $menus = $this->sysMenuService->selectMenuTreeByUserId($userInfo['userId']);
        return (new AjaxResult())->success($menus);
    }

    /**
     * 退出登录
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $loginUser = $this->tokenService->getLoginUser();
        if($loginUser != null){
            $this->tokenService->logout($loginUser);
            $request->attributes->set('userName',$loginUser['sysUser']['userName']);
        }
        return (new AjaxResult())->msg('退出成功');
    }

}

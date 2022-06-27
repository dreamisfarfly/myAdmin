<?php

namespace App\Admin\Controllers\System\Monitor;

use App\Admin\Core\Constant\Constants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Request\System\BaseQueryRequest;
use App\Admin\Service\System\Impl\SysPostServiceImpl;
use App\Admin\Service\System\Impl\SysUserOnlineServiceImpl;
use App\Admin\Service\System\ISysUserOnlineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

/**
 * 在线用户监控
 *
 * @author zjj
 */
class SysUserOnlineController extends BaseController
{

    /**
     * @var ISysUserOnlineService|SysPostServiceImpl
     */
    private ISysUserOnlineService $sysUserOnlineService;

    /**
     * @param SysUserOnlineServiceImpl $sysUserOnlineService
     */
    public function __construct(SysUserOnlineServiceImpl $sysUserOnlineService)
    {
        $this->sysUserOnlineService = $sysUserOnlineService;
    }

    /**
     * 在线用户列表
     *
     * @PreAuthorize(hasPermi = "monitor:online:list")
     */
    public function list(BaseQueryRequest $baseQueryRequest)
    {
        return $this->sysUserOnlineService->loginUserToUserOnline(
            $baseQueryRequest->getParamsData(['userName','ipaddr'])
        );
    }

    /**
     * 强退用户
     *
     * @PreAuthorize(hasPermi = "monitor:online:forceLogout")
     * @Log(title = "在线用户监控管理", businessType = BusinessType.FORCE)
     */
    public function forceLogout(string $tokenId): JsonResponse
    {
        Redis::delete(Constants::LOGIN_TOKEN_KEY.$tokenId);
        return (new AjaxResult())->success();
    }

}

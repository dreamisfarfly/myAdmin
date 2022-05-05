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
     */
    public function list(BaseQueryRequest $baseQueryRequest)
    {
        Authentication::hasPermit('monitor:online:list');
        return $this->sysUserOnlineService->loginUserToUserOnline(
            $baseQueryRequest->getParamsData(['userName'])
        );
    }

    /**
     * 强退用户
     */
    public function forceLogout(string $tokenId): JsonResponse
    {
        Authentication::hasPermit('monitor:online:forceLogout');
        Redis::delete(Constants::LOGIN_TOKEN_KEY.$tokenId);
        return (new AjaxResult())->success();
    }

}

<?php

namespace App\Admin\Controllers\System\Monitor;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Request\System\SysLoginInForListRequest;
use App\Admin\Service\System\Impl\SysLoginInForServiceImpl;
use App\Admin\Service\System\ISysLoginInForService;
use Illuminate\Http\JsonResponse;

/**
 * 系统访问记录
 */
class SysLoginInForController extends BaseController
{

    /**
     * @var ISysLoginInForService|SysLoginInForServiceImpl
     */
    private ISysLoginInForService $sysLoginInForService;

    /**
     * @param SysLoginInForServiceImpl $sysLoginInForService
     */
    public function __construct(SysLoginInForServiceImpl $sysLoginInForService)
    {
        $this->sysLoginInForService = $sysLoginInForService;
    }

    /**
     * 列表
     *
     * @param SysLoginInForListRequest $sysLoginInForListRequest
     * @return JsonResponse
     */
    public function list(SysLoginInForListRequest $sysLoginInForListRequest): JsonResponse
    {
        Authentication::hasPermit('monitor:logininfor:list');
        return $this->getDataTable(
            $this->sysLoginInForService->selectLoginInForList(
                $sysLoginInForListRequest->getParamsData(['ipaddr','userName','status','beginTime','endTime'])
            )
        );
    }

    /**
     * 删除系统访问日志
     *
     * @Log(title = "登录日志管理", businessType = BusinessType.DELETE)
     * @param string $infoIds
     * @return JsonResponse
     */
    public function remove(string $infoIds): JsonResponse
    {
        Authentication::hasPermit('monitor:logininfor:remove');
        $infoIds = explode(',', $infoIds);
        return $this->toAjax($this->sysLoginInForService->deleteLoginInForByIds($infoIds));
    }

    /**
     * 清空登录日志
     *
     * @Log(title = "登录日志管理", businessType = BusinessType.CLEAN)
     * @return JsonResponse
     */
    public function clean(): JsonResponse
    {
        Authentication::hasPermit('monitor:logininfor:remove');
        $this->sysLoginInForService->cleanLoginInFor();
        return (new AjaxResult())->success();
    }

}

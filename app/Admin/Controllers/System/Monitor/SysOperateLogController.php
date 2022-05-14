<?php

namespace App\Admin\Controllers\System\Monitor;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Service\System\Impl\SysOperLogServiceImpl;
use App\Admin\Service\System\ISysConfigService;
use App\Admin\Service\System\ISysOperLogService;
use Illuminate\Http\JsonResponse;

/**
 * 操作日志记录
 *
 * @author zjj
 */
class SysOperateLogController extends BaseController
{

    /**
     * @var ISysOperLogService|SysOperLogServiceImpl
     */
    private ISysOperLogService $sysOperLogService;

    /**
     * @param SysOperLogServiceImpl $sysOperLogService
     */
    public function __construct(SysOperLogServiceImpl $sysOperLogService)
    {
        $this->sysOperLogService = $sysOperLogService;
    }

    /**
     * 操作日志列表
     */
    public function list(): JsonResponse
    {
        Authentication::hasPermit('monitor:operlog:list');
        return $this->getDataTable(
            $this->sysOperLogService->selectOperLogList([])
        );
    }

    /**
     * 操作日志删除
     */
    public function remove(string $operIds): JsonResponse
    {
        Authentication::hasPermit('monitor:operlog:remove');
        $operIds = explode(',', $operIds);
        return $this->toAjax($this->sysOperLogService->deleteOperLogByIds($operIds));
    }

    /**
     * 操作日志全部清除
     */
    public function clean(): JsonResponse
    {
        Authentication::hasPermit('monitor:operlog:remove');
        $this->sysOperLogService->cleanOperLog();
        return (new AjaxResult())->success();
    }

}

<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Service\System\Impl\SysConfigServiceImpl;
use App\Admin\Service\System\ISysConfigService;
use Illuminate\Http\JsonResponse;

/**
 * 参数配置 信息操作处理
 *
 * @author zjj
 */
class SysConfigController extends BaseController
{

    /**
     * @var ISysConfigService
     */
    private ISysConfigService $sysConfigService;

    /**
     * @param SysConfigServiceImpl $sysConfigService
     */
    public function __construct(SysConfigServiceImpl $sysConfigService)
    {
        $this->sysConfigService = $sysConfigService;
    }

    /**
     * 根据参数键名查询参数值
     *
     * @param string $configKey
     * @return JsonResponse
     */
    public function getConfigKey(string $configKey): JsonResponse
    {
        return (new AjaxResult())
            ->msg(
                $this->sysConfigService->selectConfigByKey($configKey)
            );
    }

}

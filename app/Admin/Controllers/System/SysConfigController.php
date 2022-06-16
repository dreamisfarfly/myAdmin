<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Request\System\SysConfigListRequest;
use App\Admin\Request\System\SysConfigRequest;
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
     * 获取参数配置列表
     *
     * @param SysConfigListRequest $sysConfigListRequest
     * @return JsonResponse
     */
    public function list(SysConfigListRequest $sysConfigListRequest): JsonResponse
    {
        Authentication::hasPermit('system:config:list');
        return $this->getDataTable(
            $this->sysConfigService
                ->selectConfigList(
                    $sysConfigListRequest->getParamsData([
                        'configName',
                        'configKey',
                        'configType',
                        'beginTime',
                        'endTime'
                    ])
                )
        );
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

    /**
     * 根据参数编号获取详细信息
     *
     * @param int $configId
     * @return JsonResponse
     */
    public function getInfo(int $configId): JsonResponse
    {
        Authentication::hasPermit('system:config:query');
        return (new AjaxResult())->success($this->sysConfigService->selectConfigById($configId));
    }

    /**
     * 新增参数配置
     *
     * @ForbidSubmit
     * @Log(title = "参数配置管理", businessType = BusinessType.INSERT)
     * @param SysConfigRequest $sysConfigRequest
     * @return JsonResponse
     */
    public function add(SysConfigRequest $sysConfigRequest): JsonResponse
    {
        Authentication::hasPermit('system:config:add');
        $sysConfig = $sysConfigRequest->getParamsData(['configKey','configName','configType','configValue','remark']);
        if(UserConstants::NOT_UNIQUE == $this->sysConfigService->checkConfigKeyUnique($sysConfig['configName']))
        {
            return (new AjaxResult())->error("新增参数" . $sysConfig['configName'] . "'失败，参数键名已存在");
        }
        $sysConfig['createBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysConfigService->insertConfig($sysConfig));
    }

    /**
     * 修改参数配置
     *
     * @ForbidSubmit
     * @Log(title = "参数配置管理", businessType = BusinessType.UPDATE)
     * @param int $configId
     * @param SysConfigRequest $sysConfigRequest
     * @return JsonResponse
     */
    public function edit(int $configId, SysConfigRequest $sysConfigRequest): JsonResponse
    {
        Authentication::hasPermit('system:config:edit');
        $sysConfig = $sysConfigRequest->getParamsData(['configKey','configName','configType','configValue','remark']);
        if(UserConstants::NOT_UNIQUE == $this->sysConfigService->checkConfigKeyUnique($sysConfig['configName'], $configId))
        {
            return (new AjaxResult())->error("修改参数" . $sysConfig['configName'] . "'失败，参数键名已存在");
        }
        $sysConfig['updateBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysConfigService->updateConfig($configId, $sysConfig));
    }

    /**
     * 删除参数配置
     *
     * @Log(title = "参数配置管理", businessType = BusinessType.DELETE)
     * @param string $configIds
     * @return JsonResponse
     * @throws ParametersException
     */
    public function remove(string $configIds): JsonResponse
    {
        Authentication::hasPermit('system:config:remove');
        $configIds = explode(',', $configIds);
        return $this->toAjax($this->sysConfigService->deleteConfigByIds($configIds));
    }

    /**
     * 清空缓存
     *
     * @Log(title = "参数配置管理", businessType = BusinessType.CLEAN)
     * @return JsonResponse
     */
    public function clearCache(): JsonResponse
    {
        Authentication::hasPermit('system:config:remove');
        $this->sysConfigService->clearCache();
        return (new AjaxResult())->success();
    }

}

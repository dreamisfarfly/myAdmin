<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Request\System\SysDictDataListRequest;
use App\Admin\Request\System\SysDictDataRequest;
use App\Admin\Service\System\Impl\SysDictDataServiceImpl;
use App\Admin\Service\System\ISysDictDataService;
use Illuminate\Http\JsonResponse;

/**
 * 数据字典信息
 *
 * @author zjj
 */
class SysDictDataController extends BaseController
{

    /**
     * @var ISysDictDataService
     */
    protected ISysDictDataService $sysDictDataService;

    /**
     * @param SysDictDataServiceImpl $sysDictDataServiceImpl
     */
    public function __construct(SysDictDataServiceImpl $sysDictDataServiceImpl)
    {
        $this->sysDictDataService = $sysDictDataServiceImpl;
    }

    /**
     * 字典数据列表
     */
    public function list(SysDictDataListRequest $sysDictDataListRequest): JsonResponse
    {
        Authentication::hasPermit('system:dict:list');
        return $this->getDataTable(
            $this->sysDictDataService->selectDictDataList(
                $sysDictDataListRequest->getParamsData(['dictType','status','dictLabel'])
            )
        );
    }

    /**
     * 查询字典数据详细
     */
    public function getInfo(int $dictCode): JsonResponse
    {
        Authentication::hasPermit('system:dict:query');
        return (new AjaxResult())->success($this->sysDictDataService->selectDictDataById($dictCode));
    }

    /**
     * 根据字典类型查询字典数据信息
     */
    public function dictType(string $dictType): JsonResponse
    {
        $data = $this->sysDictDataService->selectDictDataByType($dictType);
        return (new AjaxResult())->success($data);
    }

    /**
     * 新增字典类型
     */
    public function add(SysDictDataRequest $sysDictDataRequest): JsonResponse
    {
        Authentication::hasPermit('system:dict:add');
        $sysDictData = $sysDictDataRequest->getParamsData(['cssClass','dictLabel','dictSort','dictType','dictValue','listClass','remark','status']);
        $sysDictData['createBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysDictDataService->insertDictData($sysDictData));
    }

    /**
     * 修改保存字典类型
     */
    public function edit(int $dictCode, SysDictDataRequest $sysDictDataRequest): JsonResponse
    {
        Authentication::hasPermit('system:dict:edit');
        $sysDictData = $sysDictDataRequest->getParamsData(['cssClass','dictLabel','dictSort','dictType','dictValue','listClass','remark','status']);
        $sysDictData['updateBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysDictDataService->updateDictData($dictCode, $sysDictData));
    }

    /**
     * 修改保存字典类型
     */
    public function remove(string $ids): JsonResponse
    {
        Authentication::hasPermit('system:dict:remove');
        $ids = explode(',', $ids);
        return $this->toAjax($this->sysDictDataService->deleteDictDataByIds($ids));
    }

}

<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Service\System\Impl\SysDictDataServiceImpl;
use App\Admin\Service\System\Impl\SysDictTypeServiceImpl;
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
    public function list(): JsonResponse
    {
        Authentication::hasPermit('system:dict:list');
        return $this->getDataTable($this->sysDictDataService->selectDictDataList());
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
    public function dictType()
    {

    }

    /**
     * 新增字典类型
     */
    public function add()
    {
        Authentication::hasPermit('system:dict:add');
    }

    /**
     * 修改保存字典类型
     */
    public function edit()
    {
        Authentication::hasPermit('system:dict:edit');
    }

    /**
     * 修改保存字典类型
     */
    public function remove()
    {
        Authentication::hasPermit('system:dict:remove');
    }

}

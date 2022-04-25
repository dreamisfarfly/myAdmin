<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\Authentication;
use App\Admin\Request\System\SysDictType;
use App\Admin\Service\System\Impl\SysDictTypeServiceImpl;
use App\Admin\Service\System\ISysDictTypeService;
use Illuminate\Http\JsonResponse;

/**
 * 数据字典信息
 *
 * @author zjj
 */
class SysDictTypeController extends BaseController
{

    /**
     * @var ISysDictTypeService
     */
    private ISysDictTypeService $sysDictTypeService;

    /**
     * @param SysDictTypeServiceImpl $sysDictTypeService
     */
    public function __construct(SysDictTypeServiceImpl $sysDictTypeService)
    {
        $this->sysDictTypeService = $sysDictTypeService;
    }

    /**
     * 列表
     */
    public function list(): JsonResponse
    {
        Authentication::hasPermit('system:dict:list');
        return $this->getDataTable($this->sysDictTypeService->selectDictTypeList());
    }

    /**
     * 查询字典类型详细
     */
    public function getInfo(int $id): JsonResponse
    {
        Authentication::hasPermit('system:dict:query');
        return (new AjaxResult())->success($this->sysDictTypeService->selectDictTypeById($id));
    }

    /**
     * 新增字典类型
     * @throws ParametersException
     */
    public function add(SysDictType $sysDictType): JsonResponse
    {
        Authentication::hasPermit('system:dict:add');
        if(UserConstants::NOT_UNIQUE == $this->sysDictTypeService->checkDictTypeUnique($sysDictType->get('dictId'),$sysDictType->get('dictType')))
        {
            throw new ParametersException('新增字典失败，字典类型已存在');
        }
        return $this->toAjax($this->sysDictTypeService->insertDictType([]));
    }

    /**
     * 修改字典类型
     */
    public function edit()
    {
        Authentication::hasPermit('system:dict:edit');
    }

    /**
     * 删除字典类型
     */
    public function remove()
    {
        Authentication::hasPermit('system:dict:remove');
    }

    /**
     * 清空缓存
     */
    public function clearCache()
    {
        Authentication::hasPermit('system:dict:remove');
    }

    /**
     * 获取字典选择框列表
     */
    public function optionSelect()
    {

    }

}

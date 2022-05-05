<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Security\Authentication;
use App\Admin\Request\System\SysDept;
use App\Admin\Request\System\SysDeptRequest;
use App\Admin\Service\System\Impl\SysDeptServiceImpl;
use App\Admin\Service\System\ISysDeptService;
use Illuminate\Http\JsonResponse;

/**
 * 部门信息
 *
 * @author zjj
 */
class SysDeptController extends BaseController
{

    /**
     * @var ISysDeptService
     */
    private ISysDeptService $sysDeptService;

    /**
     * @param SysDeptServiceImpl $sysDeptService
     */
    public function __construct(SysDeptServiceImpl $sysDeptService)
    {
        $this->sysDeptService = $sysDeptService;
    }

    /**
     * 获取部门列表
     */
    public function list(SysDeptRequest $sysDeptRequest): JsonResponse
    {
        Authentication::hasPermit('system:dept:list');
        return (new AjaxResult())
            ->success(
                $this->sysDeptService->selectDeptList($sysDeptRequest->getParamsData([]))
            );
    }

    /**
     * 查询部门列表（排除节点）
     */
    public function excludeChild(int $deptId = 0)
    {
        Authentication::hasPermit('system:dept:list');
        $deps = $this->sysDeptService->selectDeptList([]);
//        foreach ($deps as $item){
//            if($item['deptId'] == $deptId || )
//        }
    }

    /**
     * 根据部门编号获取详细信息
     */
    public function getInfo()
    {
        Authentication::hasPermit('system:dept:query');
    }

    /**
     * 获取部门下拉树列表
     */
    public function treeSelect(): JsonResponse
    {
        $deps = $this->sysDeptService->selectDeptList();
        return (new AjaxResult())
            ->success(
                $this->sysDeptService->buildDeptTreeSelect($deps->toArray())
            );
    }

    /**
     * 加载对应角色部门列表树
     */
    public function roleDeptTreeSelect()
    {

    }

    /**
     * 新增部门
     */
    public function add(SysDept $sysDept)
    {
        Authentication::hasPermit('system:dept:add');
    }

    /**
     * 修改部门
     */
    public function edit()
    {
        Authentication::hasPermit('system:dept:edit');
    }

    /**
     * 删除部门
     */
    public function remove(int $deptId): JsonResponse
    {
        Authentication::hasPermit('system:dept:remove');
        if($this->sysDeptService->hasChildByDeptId($deptId))
            return (new AjaxResult())->error('存在下级部门,不允许删除');
        if($this->sysDeptService->checkDeptExistUser($deptId))
            return (new AjaxResult())->error('部门存在用户,不允许删除');
        return $this->toAjax($this->sysDeptService->deleteDeptById($deptId));
    }

}

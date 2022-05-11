<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Request\System\SysDept;
use App\Admin\Request\System\SysDeptListRequest;
use App\Admin\Request\System\SysDeptRequest;
use App\Admin\Service\System\Impl\SysDeptServiceImpl;
use App\Admin\Service\System\ISysDeptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
    public function list(SysDeptListRequest $sysDeptListRequest): JsonResponse
    {
        Authentication::hasPermit('system:dept:list');
        return (new AjaxResult())
            ->success(
                $this->sysDeptService->selectDeptList($sysDeptListRequest->getParamsData(['deptName','status']))
            );
    }

    /**
     * 查询部门列表（排除节点）
     */
    public function excludeChild(int $deptId = 0): JsonResponse
    {
        Authentication::hasPermit('system:dept:list');
        $deps = $this->sysDeptService->selectDeptList();
        $data = $deps->toArray();
        foreach ($data as $key => $item)
        {

            if($item['deptId'] == $deptId || in_array($deptId,explode(',', $item['ancestors'])))
            {
                unset($data[$key]);
            }
        }
        return (new AjaxResult())->success($data);
    }

    /**
     * 根据部门编号获取详细信息
     */
    public function getInfo(int $deptId): JsonResponse
    {
        Authentication::hasPermit('system:dept:query');
        return (new AjaxResult())->success($this->sysDeptService->selectDeptById($deptId));
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
     * @throws ParametersException
     */
    public function add(SysDeptRequest $sysDeptRequest): JsonResponse
    {
        Authentication::hasPermit('system:dept:add');
        $sysDept = $sysDeptRequest->getParamsData(['ancestors','deptName','email','leader','orderNum','parentId','phone','status']);
        if(UserConstants::NOT_UNIQUE == $this->sysDeptService->checkDeptUnique($sysDept))
        {
            return (new AjaxResult())->error("新增部门'" . $sysDept['deptName'] ."'失败，部门名称已存在");
        }
        $sysDept['createBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysDeptService->insertDept($sysDept));
    }

    /**
     * 修改部门
     */
    public function edit(int $deptId, SysDeptRequest $sysDeptRequest): JsonResponse
    {
        Authentication::hasPermit('system:dept:edit');
        $sysDept = $sysDeptRequest->getParamsData(['ancestors','deptName','email','leader','orderNum','parentId','phone','status']);
        if(UserConstants::NOT_UNIQUE == $this->sysDeptService->checkDeptUnique($sysDept,$deptId))
        {
            return (new AjaxResult())->error("修改部门'" . $sysDept['deptName'] ."'失败，部门名称已存在");
        }
        if($sysDept['parentId'] == $deptId)
        {
            return (new AjaxResult())->error("修改部门'" . $sysDept['deptName'] ."'失败，上级部门不能是自己");
        }
        if(UserConstants::DEPT_DISABLE == $sysDept['status'] && $this->sysDeptService->selectNormalChildrenDeptById($deptId) > 0)
        {
            return (new AjaxResult())->error("该部门包含未停用的子部门!");
        }
        $sysDept['updateBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysDeptService->updateDept($deptId, $sysDept));
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

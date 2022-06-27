<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Constant\UserConstants;
use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Exception\ParametersException;
use App\Admin\Core\Security\Authentication;
use App\Admin\Core\Security\SecurityUtils;
use App\Admin\Request\System\SysPostListRequest;
use App\Admin\Request\System\SysPostRequest;
use App\Admin\Service\System\Impl\SysPostServiceImpl;
use App\Admin\Service\System\ISysPostService;
use Illuminate\Http\JsonResponse;

/**
 * 岗位信息操作处理
 *
 * @author zjj
 */
class SysPostController extends BaseController
{

    /**
     * @var ISysPostService
     */
    private ISysPostService $sysPostService;

    /**
     * @param SysPostServiceImpl $sysPostService
     */
    public function __construct(SysPostServiceImpl $sysPostService)
    {
        $this->sysPostService = $sysPostService;
    }

    /**
     * 获取岗位列表
     *
     * @PreAuthorize(hasPermi = "system:post:query")
     * @param SysPostListRequest $sysPostListRequest
     * @return JsonResponse
     */
    public function list(SysPostListRequest $sysPostListRequest): JsonResponse
    {
        return $this->getDataTable(
            $this->sysPostService->selectPostList($sysPostListRequest->getParamsData(['postCode','postName','status']))
        );
    }

    /**
     * 根据岗位编号获取详细信息
     *
     * @PreAuthorize(hasPermi = "system:post:query")
     * @param int $postId
     * @return JsonResponse
     */
    public function getInfo(int $postId): JsonResponse
    {
        return (new AjaxResult())->success($this->sysPostService->selectPostById($postId));
    }

    /**
     * 新增岗位
     *
     * @ForbidSubmit
     * @PreAuthorize(hasPermi = "system:post:add")
     * @Log(title = "岗位管理", businessType = BusinessType.INSERT)
     * @param SysPostRequest $sysPostRequest
     * @return JsonResponse
     */
    public function add(SysPostRequest $sysPostRequest): JsonResponse
    {
        $sysPost = $sysPostRequest->getParamsData(['postName','postCode','postSort','remark','status']);
        if(UserConstants::NOT_UNIQUE == $this->sysPostService->checkPostUnique(['postName'=>$sysPost['postName']]))
        {
            return (new AjaxResult())->error("新增岗位'" . $sysPost['postName'] . "'失败，岗位名称已存在");
        }
        if(UserConstants::NOT_UNIQUE == $this->sysPostService->checkPostUnique(['postCode'=>$sysPost['postCode']]))
        {
            return (new AjaxResult())->error("新增岗位'". $sysPost['postName'] . "'失败，岗位编码已存在");
        }
        $sysPost['createBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysPostService->insertPost($sysPost));
    }

    /**
     * 修改岗位
     *
     * @ForbidSubmit
     * @PreAuthorize(hasPermi = "system:post:edit")
     * @Log(title = "岗位管理", businessType = BusinessType.UPDATE)
     * @param int $postId
     * @param SysPostRequest $sysPostRequest
     * @return JsonResponse
     */
    public function edit(int $postId, SysPostRequest $sysPostRequest): JsonResponse
    {
        $sysPost = $sysPostRequest->getParamsData(['postName','postCode','postSort','remark','status']);
        if(UserConstants::NOT_UNIQUE == $this->sysPostService->checkPostUnique(['postName'=>$sysPost['postName']],$postId))
        {
            return (new AjaxResult())->error("新增岗位'" . $sysPost['postName'] . "'失败，岗位名称已存在");
        }
        if(UserConstants::NOT_UNIQUE == $this->sysPostService->checkPostUnique(['postCode'=>$sysPost['postCode']],$postId))
        {
            return (new AjaxResult())->error("新增岗位'". $sysPost['postName'] . "'失败，岗位编码已存在");
        }
        $sysPost['updateBy'] = SecurityUtils::getUsername();
        return $this->toAjax($this->sysPostService->updatePost($postId, $sysPost));
    }

    /**
     * 删除岗位
     *
     * @PreAuthorize(hasPermi = "system:post:remove")
     * @Log(title = "岗位管理", businessType = BusinessType.DELETE)
     * @param string $ids
     * @return JsonResponse
     * @throws ParametersException
     */
    public function remove(string $ids): JsonResponse
    {
        $ids = explode(',', $ids);
        return $this->toAjax($this->sysPostService->deletePostByIds($ids));
    }

}

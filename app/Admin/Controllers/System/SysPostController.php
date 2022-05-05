<?php

namespace App\Admin\Controllers\System;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Security\Authentication;
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
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        Authentication::hasPermit('system:role:query');
        return $this->getDataTable(
            $this->sysPostService->selectPostList([])
        );
    }

}

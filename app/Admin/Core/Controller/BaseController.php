<?php

namespace App\Admin\Core\Controller;

use App\Admin\Core\Constant\CustomStatus;
use App\Admin\Core\Domain\TableDataInfo;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

/**
 * 后台控制器基类
 *
 * @author zjj
 */
class BaseController extends Controller
{

    /**
     * 分页
     * @param LengthAwarePaginator $lengthAwarePaginator
     * @return JsonResponse
     */
    public function getDataTable(LengthAwarePaginator $lengthAwarePaginator): JsonResponse
    {
        $tableDataInfo = new TableDataInfo($lengthAwarePaginator->total(), $lengthAwarePaginator->items(),CustomStatus::SUCCESS,'查询成功');
        return $tableDataInfo->success();
    }

}

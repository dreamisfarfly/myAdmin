<?php

namespace App\Admin\Service\System\Impl;

use App\Admin\Core\Constant\Constants;
use App\Admin\Core\Constant\CustomStatus;
use App\Admin\Core\Domain\TableDataInfo;
use App\Admin\Core\Utils\PagingParametersUtil;
use App\Admin\Service\System\ISysUserOnlineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

/**
 * 在线用户 服务层
 *
 * @author zjj
 */
class SysUserOnlineServiceImpl implements ISysUserOnlineService
{

    /**
     * 设置在线用户信息
     *
     * @param array $loginUser 用户信息
     * @return JsonResponse 在线用户
     */
    function loginUserToUserOnline(array $loginUser): JsonResponse
    {
        $redisUserList = Redis::keys(Constants::LOGIN_TOKEN_KEY.'*');
        $pageNum = PagingParametersUtil::getPagingParam('pageNum');
        $pageSize = PagingParametersUtil::getPagingParam('pageSize',10);
        $dataList = array_slice($redisUserList,($pageNum-1)*$pageSize, $pageSize);
        $items = [];
        foreach ($dataList as $item)
        {
            $loginUserData = json_decode(Redis::get($item),true);
            if(isset($loginUser['userName']) && !preg_match('/^'.$loginUser['userName'].'/',$loginUserData['sysUser']['userName']))
            {
                break;
            }
            if(isset($loginUser['ipaddr']) && !preg_match('/^'.$loginUser['ipaddr'].'/',$loginUserData['ipaddr']))
            {
                break;
            }
            array_push($items,[
                'tokenId' => $loginUserData['token'],
                'deptName' => $loginUserData['sysUser']['deptName'],
                'userName' => $loginUserData['sysUser']['userName'],
                'ipaddr' => $loginUserData['ipaddr'],
                'browser' => $loginUserData['browser'],
                'loginLocation' => $loginUserData['loginLocation'],
                'os' => $loginUserData['os'],
                'loginTime' => date('Y-m-d H:i:s', $loginUserData['loginTime'])
            ]);
        }
        $tableDataInfo = new TableDataInfo(count($dataList), $items,CustomStatus::SUCCESS,'查询成功');
        return $tableDataInfo->success();
    }

}

<?php

namespace App\Admin\Core\Domain;

use App\Admin\Core\Constant\HttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * 操作消息提醒
 *
 * @author zjj
 */
class AjaxResult
{

    /**
     * 操作成功
     * @param object|null $data
     * @return JsonResponse
     */
    public static function success(?object $data = null): JsonResponse
    {
        return self::standardOutput(0, '操作成功', $data);
    }

    /**
     * 操作失败
     * @param string $msg
     * @return JsonResponse
     */
    public static function error(string $msg = '操作失败'): JsonResponse
    {
        return self::standardOutput(1, $msg);
    }

    /**
     * 标准输出API JSON格式
     * @param $code
     * @param $msg
     * @param $data
     * @return JsonResponse
     */
    public static function standardOutput($code, $msg, $data = null): JsonResponse
    {
        return Response::json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ],HttpStatus::SUCCESS);
    }

}

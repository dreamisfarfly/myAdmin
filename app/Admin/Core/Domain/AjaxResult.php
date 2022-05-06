<?php

namespace App\Admin\Core\Domain;

use App\Admin\Core\Constant\CustomStatus;
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
     * put加带数据
     * @var array
     */
    private array $putData = [];

    /**
     * 添加JSON返回
     * @param $putData
     * @return $this
     */
    public function put($putData): AjaxResult
    {
        $this->putData = $putData;
        return $this;
    }

    /**
     * 操作成功
     * @param  $data
     * @return JsonResponse
     */
    public function success($data = null): JsonResponse
    {
        return $this->standardOutput(CustomStatus::SUCCESS, '操作成功', $data);
    }

    /**
     * 返回成功消息
     * @param $msg
     * @return JsonResponse
     */
    public function msg($msg): JsonResponse
    {
        return $this->standardOutput(CustomStatus::SUCCESS, $msg);
    }

    /**
     * 操作失败
     * @param string $msg
     * @return JsonResponse
     */
    public function error(string $msg = '操作失败'): JsonResponse
    {
        return $this->standardOutput(CustomStatus::OPERATION_FAILURE, $msg);
    }

    /**
     * 标准输出API JSON格式
     * @param $code
     * @param $msg
     * @param $data
     * @return JsonResponse
     */
    public function standardOutput($code, $msg, $data = null): JsonResponse
    {
        $jsonData = [
            'code' => $code,
            'msg' => $msg,
        ];
        if($data != null)
            $jsonData['data'] = $data;
        if(count($this->putData) != 0)
            $jsonData = array_merge($jsonData,$this->putData);
        return Response::json($jsonData,HttpStatus::SUCCESS);
    }

}

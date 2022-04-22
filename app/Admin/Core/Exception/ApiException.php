<?php

namespace App\Admin\Core\Exception;

use App\Admin\Core\Domain\AjaxResult;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * 自定义状态码
 *
 * @author zjj
 */
class ApiException extends Exception
{

    /**
     * 返回消息
     * @var string
     */
    protected string $coreMsg;

    /**
     * 自定义状态码
     * @var int
     */
    protected int $coreErrorCode;

    /**
     * 报告异常
     *
     * @return false
     */
    public function report(): bool
    {
        return true;
    }

    /**
     * 渲染异常为 HTTP 响应
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return AjaxResult::standardOutput($this->coreErrorCode,$this->coreMsg);
    }

}

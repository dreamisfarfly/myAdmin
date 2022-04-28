<?php

namespace App\Admin\Controllers\System\Common;

use App\Admin\Core\Controller\BaseController;
use App\Admin\Core\Domain\AjaxResult;
use App\Admin\Core\Utils\SecurityCodeUtils;
use App\Admin\Core\Utils\Uuid\IdUtils;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

/**
 * 验证码操作处理
 *
 * @author zjj
 */
class CaptchaController extends BaseController
{

    /**
     * 生成验证码
     * @throws Exception
     */
    public function getCode(): JsonResponse
    {
        $captcha = new SecurityCodeUtils();
        $uuid = IdUtils::fastUUID();
        $value = $captcha->init();
        Redis::setex($uuid,180,$value);
        $bas64 = $captcha->outputImage();
        return (new AjaxResult())->put([
            'img' => $bas64,
            'uuid' => $uuid
        ])->success();
    }

}

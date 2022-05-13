<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyDateRange;

/**
 * 系统访问记录列表参数验证
 *
 * @author zjj
 */
class SysLoginInForListRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => [
                'in:0,1'
            ],
            'beginTime' => [
                new VerifyDateRange()
            ],
            'endTime' => [
                new VerifyDateRange()
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'status.in' => '状态类型不正确',
        ];
    }

}

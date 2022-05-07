<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyDateRange;

/**
 * 用户列表查询请求验证
 */
class SysUserListRequest extends BaseRequest
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
            'beginTime' => '开始时间格式不正确',
            'endTime' => '结束时间不正确',
        ];
    }

}

<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyDateRange;

/**
 * 角色列表查询参数验证
 *
 * @author zjj
 */
class SysRoleListRequest extends BaseRequest
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

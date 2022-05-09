<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyId;

/**
 * 编辑用户状态
 *
 * @author zjj
 */
class SysUserChangeStatusRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
                new VerifyId()
            ],
            'status' => 'in:0,1',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'userId.required' => '用户编号不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态类型不正确',
        ];
    }

}

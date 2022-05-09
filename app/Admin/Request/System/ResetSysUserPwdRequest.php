<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 重置用户密码参数验证
 *
 * @author zjj
 */
class ResetSysUserPwdRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'userId' => 'required',
            'password' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'userId.required' => '用户编号不能为空',
            'password.required' => '密码不能为空',
        ];
    }

}

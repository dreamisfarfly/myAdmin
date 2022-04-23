<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 用户登录对象
 *
 * @author zjj
 */
class LoginBody extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'code' => 'required',
            'uuid' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'code.required' => 'code不能为空',
            'uuid.required' => 'uuid不能为空',
        ];
    }

}

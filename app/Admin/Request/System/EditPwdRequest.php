<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 编辑系统用户密码
 *
 * @author zjj
 */
class EditPwdRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'oldPassword' => [
                'required'
            ],
            'newPassword' => [
                'required'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'oldPassword.required' => '旧密码不能为空',
            'newPassword.required' => '新密码不能为空'
        ];
    }

}

<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyEmail;
use App\Admin\Validators\System\VerifyPhone;

/**
 * 更新个人信息参数验证
 *
 * @author zjj
 */
class UpdateProfileRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nickName' => [
                'required'
            ],
            'phonenumber' => [
                'required',
                new VerifyPhone()
            ],
            'email' => [
                'required',
                new VerifyEmail()
            ],
            'sex' => [
                'required',
                'in:0,1'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'nickName.required' => '用户昵称不能为空',
            'phonenumber.required' => '手机号码不能为空',
            'email.required' => '邮箱不能为空',
            'sex.required' => '性别不能为空',
            'sex.in' => '性别类型不正确',
        ];
    }

}

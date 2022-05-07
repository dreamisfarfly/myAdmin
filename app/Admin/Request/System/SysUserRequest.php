<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyEmail;
use App\Admin\Validators\System\VerifyId;
use App\Admin\Validators\System\VerifyIds;
use App\Admin\Validators\System\VerifyPhone;

/**
 * 用户参数验证
 *
 * @author zjj
 */
class SysUserRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'nickName' => 'required',
            'password' => 'required',
            'status' => [
                'required',
                'in:0,1'
            ],
            'userName' => 'required',
            'postIds' => new VerifyIds(),
            'roleIds' =>  new VerifyIds(),
            'email' => new VerifyEmail(),
            'phonenumber' => new VerifyPhone(),
            'sex' => 'in:0,1,2',
            'deptId' => new VerifyId()
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'nickName.required' => '用户昵称不能为空',
            'password.required' => '密码不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态类型不正确',
            'userName.required' => '用户名称不能为空',
            'sex.in' => '性别类型不正确'
        ];
    }

}

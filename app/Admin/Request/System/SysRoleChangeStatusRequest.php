<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyId;

/**
 * 编辑角色状态
 *
 * @author zjj
 */
class SysRoleChangeStatusRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'roleId' => [
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
            'userId.required' => '角色编号不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态类型不正确',
        ];
    }

}

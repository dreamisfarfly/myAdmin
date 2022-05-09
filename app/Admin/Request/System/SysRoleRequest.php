<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyIds;
use App\Admin\Validators\System\VerifyPositiveInteger;

/**
 * 角色参数验证
 *
 * @author zjj
 */
class SysRoleRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'deptCheckStrictly' => 'required',
            'deptIds' => [
                new VerifyIds()
            ],
            'menuCheckStrictly' => 'required',
            'menuIds' => [
                new VerifyIds()
            ],
            'roleKey' => 'required',
            'roleName' => 'required',
            'roleSort' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'status' => [
                'required',
                'in:0,1'
            ],
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

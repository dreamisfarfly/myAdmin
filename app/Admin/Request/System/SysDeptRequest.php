<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyEmail;
use App\Admin\Validators\System\VerifyPhone;
use App\Admin\Validators\System\VerifyPositiveInteger;


/**
 * 部门
 *
 * @author zjj
 */
class SysDeptRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'parentId' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'deptName' => [
                'required',
            ],
            'orderNum' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'status' => [
                'required',
                'in:0,1'
            ],
            'email' => [
                new VerifyEmail()
            ],
            'phone' => [
                new VerifyPhone()
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'parentId.required' => '上级部门不能为空',
            'deptName.required' => '部门名称不能为空',
            'orderNum.required' => '显示排序不能为空',
            'status.required' => '部门状态不能为空',
            'status.in' => '部门状态范围不正确',
        ];
    }

}

<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 部门
 *
 * @author zjj
 */
class SysDept extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'parentId' => 'required',
            'ancestors' => 'required',
            'deptName' => 'required',
            'orderNum' => 'required',
            'leader' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'status' => [
                'required',
                'in:0,1'
            ],
            'delFlag' => [
                'required',
                'in:0,2'
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'parentId.required' => '父部门编号不能为空',
            'ancestors.required' => '祖级列表不能为空',
            'deptName.required' => '部门名称不能为空',
            'orderNum.required' => '显示顺序不能为空',
            'leader.required' => '负责人不能为空',
            'phone.required' => '联系电话不能为空',
            'email.required' => '邮箱不能为空',
            'status.required' => '部门状态不能为空',
            'delFlag.required' => '删除标志不能为空',
        ];
    }

}

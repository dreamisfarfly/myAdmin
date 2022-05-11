<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyPositiveInteger;

/**
 * 编辑岗位参数验证
 *
 * @author zjj
 */
class SysPostRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'postCode' => [
                'required',
            ],
            'postName' => [
                'required',
            ],
            'postSort' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'status' => [
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
            'postCode.required' => '岗位编码不能为空',
            'postName.required' => '岗位名称不能为空',
            'postSort.required' => '岗位顺序不能为空',
            'status.required' => '岗位状态不能为空',
            'status.in' => '岗位状态不正确'
        ];
    }
}

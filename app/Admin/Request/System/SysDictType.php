<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 字典类型
 *
 * @author zjj
 */
class SysDictType extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'dictName' => 'required',
            'dictType' => 'required',
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
            'dictName.required' => '字典名称不能为空',
            'dictType.required' => '字典类型不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态范围不正确',
        ];
    }

}

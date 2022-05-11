<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyPositiveInteger;

/**
 * 字典数据编辑参数认证
 *
 * @author zjj
 */
class SysDictDataRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'dictType' => 'required',
            'dictLabel' => 'required',
            'dictValue' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'dictSort' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'status' => [
                'required',
                'in:0,1'
            ],
            'listClass' => [
                'required',
                'in:default'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'dictType.required' => '字典类型不能为空',
            'dictLabel.required' => '数据标签不能为空',
            'dictValue.required' => '数据键值不能为空',
            'dictSort.required' => '显示排序不能为空',
            'status.required' => '状态不能为空',
            'status.in' => '状态类型不正确',
            'listClass.required' => '回显样式不能为空',
            'listClass.in' => '回显样式类型不正确',
        ];
    }

}

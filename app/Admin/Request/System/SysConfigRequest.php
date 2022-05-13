<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 参数配置编辑验证
 *
 * @author zjj
 */
class SysConfigRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'configKey' => [
                'required'
            ],
            'configName' => [
                'required'
            ],
            'configType' => [
                'required',
                'in:Y,N'
            ],
            'configValue' => [
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
            'configKey.required' => '参数键名不能为空',
            'configName.required' => '参数名称不能为空',
            'configType.required' => '系统内置类型不能为空',
            'configType.in' => '系统内置类型状态不正确',
            'configValue.required' => '参数键值不能为空',
        ];
    }

}

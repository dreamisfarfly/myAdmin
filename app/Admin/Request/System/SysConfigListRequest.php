<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 参数配置列表参数验证
 *
 * @author zjj
 */
class SysConfigListRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'configType' => [
                'in:Y,N'
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'configType.in' => '系统内置类型状态不正确'
        ];
    }

}

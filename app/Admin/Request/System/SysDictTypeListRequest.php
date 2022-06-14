<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 字典类型请求验证
 *
 * @author zjj
 */
class SysDictTypeListRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => [
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
            'status.in' => '状态范围不正确',
        ];
    }

}

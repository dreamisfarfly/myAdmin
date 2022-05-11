<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 字典数据列表搜索参数验证
 *
 * @author zjj
 */
class SysDictDataListRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => [
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
            'status.in' => '字典数据状态不正确'
        ];
    }

}

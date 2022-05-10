<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 部门列表请求参数验证
 *
 * @author zjj
 */
class SysDeptListRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'status' => 'in:0,1'
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'status.in' => '部门菜单状态不正确'
        ];
    }

}

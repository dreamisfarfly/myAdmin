<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;

/**
 * 菜单列表参数
 *
 * @author zjj
 */
class SysMenuListRequest extends BaseRequest
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
            'status.in' => '状态类型不正确'
        ];
    }

}

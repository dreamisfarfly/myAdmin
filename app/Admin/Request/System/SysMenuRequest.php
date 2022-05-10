<?php

namespace App\Admin\Request\System;

use App\Admin\Core\Request\BaseRequest;
use App\Admin\Validators\System\VerifyPositiveInteger;
use App\Admin\Validators\System\VerifyUrl;

/**
 * 菜单
 *
 * @author zjj
 */
class SysMenuRequest extends BaseRequest
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'menuName' => [
                'required'
            ],
            'orderNum' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'menuType' => [
                'required',
                'in:M,C,F'
            ],
            'parentId' => [
                'required',
                new VerifyPositiveInteger()
            ],
            'status' => [
                'in:0,1'
            ],
            'visible' => [
                'in:0,1'
            ],
            'isCache' => [
                'in:0,1'
            ],
            'isFrame' => [
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
            'menuName.required' => '菜单名称不能为空',
            'orderNum.required' => '显示顺序不能为空',
            'menuType.required' => '菜单类型不能为空',
            'menuType.in' => '菜单类型不正确',
            'parentId.required' => '父菜单ID不正确',
            'status.in' => '菜单状态不正确',
            'visible.in' => '显示状态不正确',
            'isCache.in' => '是否缓存状态不正确',
            'isFrame.in' => '是否为外链状态不正确',
        ];
    }

}

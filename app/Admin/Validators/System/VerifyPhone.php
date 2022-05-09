<?php

namespace App\Admin\Validators\System;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证手机号码
 *
 * @author zjj
 */
class VerifyPhone implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($value == null)
            return true;
        if(preg_match('/^1[3|4|5|7|8]\d{9}$/',$value))
            return true;
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return '手机号码格式不正确';
    }
}

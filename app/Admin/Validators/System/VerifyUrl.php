<?php

namespace App\Admin\Validators\System;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证URL
 *
 * @author zjj
 */
class VerifyUrl implements Rule
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
        if(preg_match('/(http|https):\/\/([\w.]+\/?)\S*/',$value))
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
        return 'url格式不正确';
    }
}

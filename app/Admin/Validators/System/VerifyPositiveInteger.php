<?php

namespace App\Admin\Validators\System;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证正整数
 *
 * @author zjj
 */
class VerifyPositiveInteger implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if($value == null)
            return true;
        if(preg_match('^[+]{0,1}(\d+)$',$value))
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
        return '必须为正整数';
    }
}

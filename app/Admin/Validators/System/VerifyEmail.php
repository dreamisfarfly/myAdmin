<?php

namespace App\Admin\Validators\System;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证邮箱
 *
 * @author zjj
 */
class VerifyEmail implements Rule
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
        if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/',$value))
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
        return '邮箱格式不正确';
    }
}

<?php

namespace App\Admin\Validators\System;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证ids
 *
 * @author zjj
 */
class VerifyIds implements Rule
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
        if(!is_array($value))
            return false;
        foreach ($value as $item)
        {
            if(!preg_match("/^[1-9][0-9]*$/",$item))
                return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'ids格式不正确';
    }
}

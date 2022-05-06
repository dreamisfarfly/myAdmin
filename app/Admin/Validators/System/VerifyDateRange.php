<?php

namespace App\Admin\Validators\System;

use Illuminate\Contracts\Validation\Rule;

/**
 * 验证日期格式
 *
 * @author zjj
 */
class VerifyDateRange implements Rule
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
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $value, $parts))
        {
            //检测是否为日期
            if(checkdate($parts[2],$parts[3],$parts[1]))
                return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        // TODO: Implement message() method.
    }
}

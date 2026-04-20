<?php

declare(strict_types=1);

namespace HopsWeb\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AttributeNotEmptyAfterTrim implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (trim((string)$value) === "") {
            $fail("The " . $attribute . " must not be empty after trimming.");
        }
    }
}

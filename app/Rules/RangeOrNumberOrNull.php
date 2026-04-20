<?php

declare(strict_types=1);

namespace HopsWeb\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RangeOrNumberOrNull implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (is_int($value) || is_float($value)) {
            if ($value < 0) {
                $fail("The {$attribute} must be a non-negative number.");
            }

            return;
        }

        if (is_array($value)) {
            if (!array_key_exists("min", $value) || !array_key_exists("max", $value)) {
                $fail("The {$attribute} range must contain both 'min' and 'max' keys.");

                return;
            }

            $min = $value["min"];
            $max = $value["max"];

            if (!is_numeric($min) || !is_numeric($max)) {
                $fail("The {$attribute} min and max must be numeric.");

                return;
            }

            $min = (float)$min;
            $max = (float)$max;

            if ($min < 0 || $max < 0) {
                $fail("The {$attribute} min and max must be non-negative.");

                return;
            }

            if ($min >= $max) {
                $fail("The {$attribute} min must be less than max.");

                return;
            }

            return;
        }

        $fail("The {$attribute} must be a number, a range object, or null.");
    }
}

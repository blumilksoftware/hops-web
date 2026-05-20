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

        $friendlyAttributes = [
            "ingredients.alphas" => "alpha acids range",
            "ingredients.betas" => "beta acids range",
            "ingredients.cohumulones" => "cohumulone range",
            "ingredients.polyphenols" => "polyphenols range",
            "ingredients.xanthohumol" => "xanthohumol range",
            "ingredients.oils" => "total oils range",
            "ingredients.farnesenes" => "farnesene range",
            "ingredients.linalool" => "linalool range",
        ];
        $displayName = $friendlyAttributes[$attribute] ?? str_replace("_", " ", $attribute);

        if (is_int($value) || is_float($value)) {
            if ($value < 0) {
                $fail("The {$displayName} must be a non-negative number.");
            }

            return;
        }

        if (is_array($value)) {
            if (!array_key_exists("min", $value) || !array_key_exists("max", $value)) {
                $fail("The {$displayName} range must contain both 'min' and 'max' keys.");

                return;
            }

            $min = $value["min"];
            $max = $value["max"];

            if (!is_numeric($min) || !is_numeric($max)) {
                $fail("The {$displayName} min and max must be numeric.");

                return;
            }

            $min = (float)$min;
            $max = (float)$max;

            if ($min < 0 || $max < 0) {
                $fail("The {$displayName} min and max must be non-negative.");

                return;
            }

            if ($min >= $max) {
                $fail("The {$displayName} min must be less than max.");

                return;
            }

            return;
        }

        $fail("The {$displayName} must be a number, a range object, or null.");
    }
}

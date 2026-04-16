<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests;

use HopsWeb\Enums\Aromaticity;
use HopsWeb\Enums\Bitterness;
use HopsWeb\Enums\HopDescriptor;
use HopsWeb\Rules\RangeOrNumberOrNull;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ComparisonQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "target" => ["nullable", "array"],
            "target.present" => ["nullable", "array"],
            "target.present.*" => ["required", "string", new AttributeNotEmptyAfterTrim()],
            "target.absent" => ["nullable", "array"],
            "target.absent.*" => ["required", "string", new AttributeNotEmptyAfterTrim()],

            "aroma" => ["nullable", "array"],
            "aroma.present" => ["nullable", "array"],
            "aroma.present.*" => ["required", "string", Rule::enum(HopDescriptor::class)],
            "aroma.absent" => ["nullable", "array"],
            "aroma.absent.*" => ["required", "string", Rule::enum(HopDescriptor::class)],

            "description" => ["nullable", "array"],
            "description.present" => ["nullable", "array"],
            "description.present.*" => ["required", "string", new AttributeNotEmptyAfterTrim()],
            "description.absent" => ["nullable", "array"],
            "description.absent.*" => ["required", "string", new AttributeNotEmptyAfterTrim()],

            "ingredients" => ["nullable", "array"],
            "ingredients.alphas" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.betas" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.cohumulones" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.polyphenols" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.xanthohumol" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.oils" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.farnesenes" => ["nullable", new RangeOrNumberOrNull()],
            "ingredients.linalool" => ["nullable", new RangeOrNumberOrNull()],

            "feeling" => ["nullable", "array"],
            "feeling.bitterness" => ["nullable", "string", Rule::enum(Bitterness::class)],
            "feeling.aromaticity" => ["nullable", "string", Rule::enum(Aromaticity::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $allowedKeys = ["target", "aroma", "description", "ingredients", "feeling"];
            $extraKeys = array_diff(array_keys($this->all()), $allowedKeys);

            if (!empty($extraKeys)) {
                $validator->errors()->add("query", "Unknown top-level keys detected: " . implode(", ", $extraKeys));
            }

            foreach (["target", "aroma", "description"] as $field) {
                $present = $this->input("{$field}.present");
                $absent = $this->input("{$field}.absent");

                if (is_array($present) && is_array($absent)) {
                    $intersect = array_intersect($present, $absent);

                    if (!empty($intersect)) {
                        $validator->errors()->add($field, "The {$field} field contains duplicate values in present and absent arrays.");
                    }
                }
            }
        });
    }
}

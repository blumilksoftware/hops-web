<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests;

use Closure;
use HopsWeb\Enums\Aromaticity;
use HopsWeb\Enums\Bitterness;
use HopsWeb\Enums\HopDescriptor;
use HopsWeb\Rules\AttributeNotEmptyAfterTrim;
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
            "aroma.present.*" => [
                "required",
                "string",
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (!HopDescriptor::tryFrom($value)) {
                        $fail("The aroma descriptor '{$value}' is invalid.");
                    }
                },
            ],
            "aroma.absent" => ["nullable", "array"],
            "aroma.absent.*" => [
                "required",
                "string",
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (!HopDescriptor::tryFrom($value)) {
                        $fail("The aroma descriptor '{$value}' is invalid.");
                    }
                },
            ],

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

    public function messages(): array
    {
        return [
            "aroma.present.*.enum" => "The selected present aroma descriptor is invalid.",
            "aroma.present.*.required" => "The present aroma descriptor field is required.",
            "aroma.present.*.string" => "The present aroma descriptor must be a string.",
            "aroma.absent.*.enum" => "The selected absent aroma descriptor is invalid.",
            "aroma.absent.*.required" => "The absent aroma descriptor field is required.",
            "aroma.absent.*.string" => "The absent aroma descriptor must be a string.",
            "target.present.*.required" => "The present target variety field is required.",
            "target.present.*.string" => "The present target variety must be a string.",
            "target.absent.*.required" => "The absent target variety field is required.",
            "target.absent.*.string" => "The absent target variety must be a string.",
            "description.present.*.required" => "The present description word field is required.",
            "description.present.*.string" => "The present description word must be a string.",
            "description.absent.*.required" => "The absent description word field is required.",
            "description.absent.*.string" => "The absent description word must be a string.",
        ];
    }

    public function attributes(): array
    {
        return [
            "target.present.*" => "present target variety",
            "target.absent.*" => "absent target variety",
            "aroma.present.*" => "present aroma descriptor",
            "aroma.absent.*" => "absent aroma descriptor",
            "description.present.*" => "present description word",
            "description.absent.*" => "absent description word",
            "ingredients.alphas" => "alpha acids range",
            "ingredients.betas" => "beta acids range",
            "ingredients.cohumulones" => "cohumulone range",
            "ingredients.polyphenols" => "polyphenols range",
            "ingredients.xanthohumol" => "xanthohumol range",
            "ingredients.oils" => "total oils range",
            "ingredients.farnesenes" => "farnesene range",
            "ingredients.linalool" => "linalool range",
        ];
    }
}

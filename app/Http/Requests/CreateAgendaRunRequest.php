<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateAgendaRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "module_weights" => ["required", "array"],
            "module_weights.aroma" => ["required", "numeric", "min:0", "max:1"],
            "module_weights.biochemical" => ["required", "numeric", "min:0", "max:1"],
            "module_weights.description" => ["required", "numeric", "min:0", "max:1"],
            "module_weights.feeling" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights" => ["required", "array"],
            "biochemical_weights.alpha_acid" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.beta_acid" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.cohumulone" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.total_oil" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.polyphenol" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.xanthohumol" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.farnesene" => ["required", "numeric", "min:0", "max:1"],
            "biochemical_weights.linalool" => ["required", "numeric", "min:0", "max:1"],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $moduleSum = array_sum(array_map("floatval", $this->input("module_weights", [])));

            if (abs($moduleSum - 1.0) > 0.001) {
                $validator->errors()->add(
                    "module_weights",
                    __("Module weights must sum to 1.0 (current: :sum).", ["sum" => number_format($moduleSum, 4)]),
                );
            }

            $biochemicalSum = array_sum(array_map("floatval", $this->input("biochemical_weights", [])));

            if (abs($biochemicalSum - 1.0) > 0.001) {
                $validator->errors()->add(
                    "biochemical_weights",
                    __("Biochemical weights must sum to 1.0 (current: :sum).", ["sum" => number_format($biochemicalSum, 4)]),
                );
            }
        });
    }

    public function getData(): array
    {
        return [
            "module_weights" => array_map("floatval", $this->input("module_weights", [])),
            "biochemical_weights" => array_map("floatval", $this->input("biochemical_weights", [])),
        ];
    }
}

<?php

declare(strict_types=1);

namespace HopsWeb\Http\Requests\Admin;

use HopsWeb\Enums\Aromaticity;
use HopsWeb\Enums\Bitterness;
use HopsWeb\Enums\HopDescriptor;
use HopsWeb\Enums\HopLineage;
use HopsWeb\Enums\HopMaturity;
use HopsWeb\Enums\Resistance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class HopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "alt_name" => ["nullable", "string", "max:255"],
            "country" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "string"],

            "descriptors" => ["nullable", "array"],
            "descriptors.*" => [Rule::enum(HopDescriptor::class)],
            "lineage" => ["nullable", "array"],
            "lineage.*" => [Rule::enum(HopLineage::class)],

            "alpha_acid_min" => ["nullable", "numeric", "min:0", "max:100"],
            "alpha_acid_max" => ["nullable", "numeric", "min:0", "gte:alpha_acid_min", "max:100"],
            "beta_acid_min" => ["nullable", "numeric", "min:0", "max:100"],
            "beta_acid_max" => ["nullable", "numeric", "min:0", "gte:beta_acid_min", "max:100"],
            "cohumulone_min" => ["nullable", "numeric", "min:0", "max:100"],
            "cohumulone_max" => ["nullable", "numeric", "min:0", "gte:cohumulone_min", "max:100"],
            "total_oil_min" => ["nullable", "numeric", "min:0", "max:100"],
            "total_oil_max" => ["nullable", "numeric", "min:0", "gte:total_oil_min", "max:100"],
            "polyphenol_min" => ["nullable", "numeric", "min:0", "max:100"],
            "polyphenol_max" => ["nullable", "numeric", "min:0", "gte:polyphenol_min", "max:100"],
            "xanthohumol_min" => ["nullable", "numeric", "min:0", "max:100"],
            "xanthohumol_max" => ["nullable", "numeric", "min:0", "gte:xanthohumol_min", "max:100"],
            "farnesene_min" => ["nullable", "numeric", "min:0", "max:100"],
            "farnesene_max" => ["nullable", "numeric", "min:0", "gte:farnesene_min", "max:100"],
            "linalool_min" => ["nullable", "numeric", "min:0", "max:100"],
            "linalool_max" => ["nullable", "numeric", "min:0", "gte:linalool_min", "max:100"],

            "aroma_citrusy" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_fruity" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_floral" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_herbal" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_spicy" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_resinous" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_sugarlike" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_misc" => ["nullable", "integer", "min:0", "max:10"],
            "aroma_descriptors" => ["nullable", "array"],
            "aroma_descriptors.*" => ["string", "max:255"],

            "thiols" => ["nullable", Rule::enum(Aromaticity::class)],
            "aromaticity" => ["nullable", Rule::enum(Aromaticity::class)],
            "bitterness" => ["nullable", Rule::enum(Bitterness::class)],
            "maturity" => ["nullable", Rule::enum(HopMaturity::class)],
            "wilt_disease" => ["nullable", Rule::enum(Resistance::class)],
            "downy_mildew" => ["nullable", Rule::enum(Resistance::class)],
            "powdery_mildew" => ["nullable", Rule::enum(Resistance::class)],
            "aphid" => ["nullable", Rule::enum(Resistance::class)],

            "yield_min" => ["nullable", "integer", "min:0", "max:100"],
            "yield_max" => ["nullable", "integer", "min:0", "gte:yield_min", "max:100"],
        ];
    }

    public function messages(): array
    {
        $messages = [];
        $rangeFields = [
            "alpha_acid" => "Alpha Acid",
            "beta_acid" => "Beta Acid",
            "cohumulone" => "Cohumulone",
            "total_oil" => "Total Oil",
            "polyphenol" => "Polyphenol",
            "xanthohumol" => "Xanthohumol",
            "farnesene" => "Farnesene",
            "linalool" => "Linalool",
            "yield" => "Yield",
        ];

        foreach ($rangeFields as $field => $label) {
            $messages["{$field}_max.gte"] = "The {$label} max must be greater than or equal to the min value.";
        }

        return $messages;
    }
}

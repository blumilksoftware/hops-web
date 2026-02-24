<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

use HopsWeb\Models\Hop;
use HopsWeb\ValueObjects\RangeOrNumber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpsertHop
{
    public function execute(array $data): Hop
    {
        $validated = $this->validate($data);

        return Hop::updateOrCreate(
            ["name" => $validated["name"]],
            $this->buildAttributes($validated),
        );
    }

    private function validate(array $data): array
    {
        $validator = Validator::make($data, [
            "name" => ["required", "string", "max:255"],
            "alt_name" => ["nullable", "string", "max:255"],
            "country" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "string"],
            "descriptors" => ["nullable", "array"],
            "descriptors.*" => ["string"],
            "lineage" => ["nullable", "array"],
            "lineage.*" => ["string"],
            "alpha_acid_min" => ["nullable", "numeric", "min:0"],
            "alpha_acid_max" => ["nullable", "numeric", "min:0"],
            "beta_acid_min" => ["nullable", "numeric", "min:0"],
            "beta_acid_max" => ["nullable", "numeric", "min:0"],
            "cohumulone_min" => ["nullable", "numeric", "min:0"],
            "cohumulone_max" => ["nullable", "numeric", "min:0"],
            "total_oil_min" => ["nullable", "numeric", "min:0"],
            "total_oil_max" => ["nullable", "numeric", "min:0"],
            "polyphenol_min" => ["nullable", "numeric", "min:0"],
            "polyphenol_max" => ["nullable", "numeric", "min:0"],
            "xanthohumol_min" => ["nullable", "numeric", "min:0"],
            "xanthohumol_max" => ["nullable", "numeric", "min:0"],
            "farnesene_min" => ["nullable", "numeric", "min:0"],
            "farnesene_max" => ["nullable", "numeric", "min:0"],
            "linalool_min" => ["nullable", "numeric", "min:0"],
            "linalool_max" => ["nullable", "numeric", "min:0"],
            "thiols" => ["nullable", "string", "in:low,medium,high"],
            "aroma_citrusy" => ["nullable", "integer", "between:0,5"],
            "aroma_fruity" => ["nullable", "integer", "between:0,5"],
            "aroma_floral" => ["nullable", "integer", "between:0,5"],
            "aroma_herbal" => ["nullable", "integer", "between:0,5"],
            "aroma_spicy" => ["nullable", "integer", "between:0,5"],
            "aroma_resinous" => ["nullable", "integer", "between:0,5"],
            "aroma_sugarlike" => ["nullable", "integer", "between:0,5"],
            "aroma_misc" => ["nullable", "integer", "between:0,5"],
            "aroma_descriptors" => ["nullable", "array"],
            "aroma_descriptors.*" => ["string"],
            "substitutes" => ["nullable", "array"],
            "substitutes.brewhouse" => ["nullable", "array"],
            "substitutes.brewhouse.*" => ["string"],
            "substitutes.dryhopping" => ["nullable", "array"],
            "substitutes.dryhopping.*" => ["string"],
            "yield_min" => ["nullable", "integer", "min:0"],
            "yield_max" => ["nullable", "integer", "min:0"],
            "maturity" => ["nullable", "string", "max:255"],
            "wilt_disease" => ["nullable", "string", "max:255"],
            "downy_mildew" => ["nullable", "string", "max:255"],
            "powdery_mildew" => ["nullable", "string", "max:255"],
            "aphid" => ["nullable", "string", "max:255"],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    private function buildAttributes(array $validated): array
    {
        $attributes = [
            "slug" => Str::slug($validated["name"]) . "-hop",
            "alt_name" => $validated["alt_name"] ?? null,
            "country" => $validated["country"] ?? null,
            "description" => $validated["description"] ?? null,
            "descriptors" => $validated["descriptors"] ?? [],
            "lineage" => $validated["lineage"] ?? [],
            "thiols" => $validated["thiols"] ?? null,
            "aroma_citrusy" => $validated["aroma_citrusy"] ?? null,
            "aroma_fruity" => $validated["aroma_fruity"] ?? null,
            "aroma_floral" => $validated["aroma_floral"] ?? null,
            "aroma_herbal" => $validated["aroma_herbal"] ?? null,
            "aroma_spicy" => $validated["aroma_spicy"] ?? null,
            "aroma_resinous" => $validated["aroma_resinous"] ?? null,
            "aroma_sugarlike" => $validated["aroma_sugarlike"] ?? null,
            "aroma_misc" => $validated["aroma_misc"] ?? null,
            "aroma_descriptors" => $validated["aroma_descriptors"] ?? [],
            "substitutes" => [
                "brewhouse" => $validated["substitutes"]["brewhouse"] ?? [],
                "dryhopping" => $validated["substitutes"]["dryhopping"] ?? [],
            ],
            "yield_min" => $validated["yield_min"] ?? null,
            "yield_max" => $validated["yield_max"] ?? null,
            "maturity" => $validated["maturity"] ?? null,
            "wilt_disease" => $validated["wilt_disease"] ?? null,
            "downy_mildew" => $validated["downy_mildew"] ?? null,
            "powdery_mildew" => $validated["powdery_mildew"] ?? null,
            "aphid" => $validated["aphid"] ?? null,
        ];

        $rangeFields = [
            "alpha_acid",
            "beta_acid",
            "cohumulone",
            "total_oil",
            "polyphenol",
            "xanthohumol",
            "farnesene",
            "linalool",
        ];

        foreach ($rangeFields as $field) {
            $min = $validated["{$field}_min"] ?? null;
            $max = $validated["{$field}_max"] ?? null;

            $attributes[$field] = ($min !== null && $max !== null)
                ? RangeOrNumber::fromRange((float)$min, (float)$max)
                : null;
        }

        return $attributes;
    }
}

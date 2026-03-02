<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

use HopsWeb\Models\Hop;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UpsertHop
{
    private const array RANGE_FIELDS = [
        "alpha_acid",
        "beta_acid",
        "cohumulone",
        "total_oil",
        "polyphenol",
        "xanthohumol",
        "farnesene",
        "linalool",
    ];

    public function execute(array $data): Hop
    {
        $rangeValues = $this->extractRangeValues($data);
        $validated = $this->validate($data);

        return Hop::updateOrCreate(
            ["name" => $validated["name"]],
            $this->buildAttributes($validated, $rangeValues),
        );
    }

    private function extractRangeValues(array $data): array
    {
        $rangeValues = [];

        foreach (self::RANGE_FIELDS as $field) {
            $rangeValues[$field] = $data[$field] ?? null;
        }

        return $rangeValues;
    }

    private function validate(array $data): array
    {
        $filteredData = array_diff_key($data, array_flip(self::RANGE_FIELDS));

        $validator = Validator::make($filteredData, [
            "name" => ["required", "string", "max:255"],
            "alt_name" => ["nullable", "string", "max:255"],
            "country" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "string"],
            "descriptors" => ["nullable", "array"],
            "descriptors.*" => ["string"],
            "lineage" => ["nullable", "array"],
            "lineage.*" => ["string"],
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

    private function buildAttributes(array $validated, array $rangeValues): array
    {
        return [
            "slug" => Str::slug($validated["name"]) . "-hop",
            "alt_name" => $validated["alt_name"] ?? null,
            "country" => $validated["country"] ?? null,
            "description" => $validated["description"] ?? null,
            "descriptors" => $validated["descriptors"] ?? [],
            "lineage" => $validated["lineage"] ?? [],
            "alpha_acid" => $rangeValues["alpha_acid"],
            "beta_acid" => $rangeValues["beta_acid"],
            "cohumulone" => $rangeValues["cohumulone"],
            "total_oil" => $rangeValues["total_oil"],
            "polyphenol" => $rangeValues["polyphenol"],
            "xanthohumol" => $rangeValues["xanthohumol"],
            "farnesene" => $rangeValues["farnesene"],
            "linalool" => $rangeValues["linalool"],
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
    }
}

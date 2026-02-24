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
            "country" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "string"],
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
            "aroma_citrusy" => ["nullable", "integer", "between:0,5"],
            "aroma_fruity" => ["nullable", "integer", "between:0,5"],
            "aroma_floral" => ["nullable", "integer", "between:0,5"],
            "aroma_herbal" => ["nullable", "integer", "between:0,5"],
            "aroma_spicy" => ["nullable", "integer", "between:0,5"],
            "aroma_resinous" => ["nullable", "integer", "between:0,5"],
            "aroma_sugarlike" => ["nullable", "integer", "between:0,5"],
            "aroma_miscellaneous" => ["nullable", "integer", "between:0,5"],
            "aroma_descriptors" => ["nullable", "array"],
            "aroma_descriptors.*" => ["string"],
            "substitutes" => ["nullable", "array"],
            "substitutes.*" => ["string"],
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
            "country" => $validated["country"],
            "description" => $validated["description"],
            "aroma_citrusy" => $validated["aroma_citrusy"],
            "aroma_fruity" => $validated["aroma_fruity"],
            "aroma_floral" => $validated["aroma_floral"],
            "aroma_herbal" => $validated["aroma_herbal"],
            "aroma_spicy" => $validated["aroma_spicy"],
            "aroma_resinous" => $validated["aroma_resinous"],
            "aroma_sugarlike" => $validated["aroma_sugarlike"],
            "aroma_miscellaneous" => $validated["aroma_miscellaneous"],
            "aroma_descriptors" => $validated["aroma_descriptors"] ?? [],
            "substitutes" => $validated["substitutes"] ?? [],
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

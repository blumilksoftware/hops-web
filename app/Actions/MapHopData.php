<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

use HopsWeb\ValueObjects\RangeOrNumber;

class MapHopData
{
    public function execute(array $data): array
    {
        $ingredients = $data["ingredients"] ?? [];
        $agronomic = $data["agronomic"] ?? [];
        $yield = $agronomic["yield"] ?? null;

        return [
            "name" => $data["name"] ?? null,
            "alt_name" => $data["altName"] ?? null,
            "country" => $data["country"] ?? null,
            "description" => $data["origin"] ?? null,
            "descriptors" => $data["descriptors"] ?? [],
            "lineage" => $data["lineage"] ?? [],
            "alpha_acid" => $this->extractRange($ingredients["alphas"] ?? null),
            "beta_acid" => $this->extractRange($ingredients["betas"] ?? null),
            "cohumulone" => $this->extractRange($ingredients["cohumulones"] ?? null),
            "total_oil" => $this->extractRange($ingredients["oils"] ?? null),
            "polyphenol" => $this->extractRange($ingredients["polyphenols"] ?? null),
            "xanthohumol" => $this->extractRange($ingredients["xanthohumols"] ?? null),
            "farnesene" => $this->extractRange($ingredients["farnesenes"] ?? null),
            "linalool" => $this->extractRange($ingredients["linalool"] ?? null),
            "thiols" => $ingredients["thiols"] ?? null,
            "aroma_citrusy" => $data["aroma"]["citrusy"] ?? null,
            "aroma_fruity" => $data["aroma"]["fruity"] ?? null,
            "aroma_floral" => $data["aroma"]["floral"] ?? null,
            "aroma_herbal" => $data["aroma"]["herbal"] ?? null,
            "aroma_spicy" => $data["aroma"]["spicy"] ?? null,
            "aroma_resinous" => $data["aroma"]["resinous"] ?? null,
            "aroma_sugarlike" => $data["aroma"]["sugarlike"] ?? null,
            "aroma_misc" => $data["aroma"]["misc"] ?? null,
            "aroma_descriptors" => $data["aromaDescription"] ?? [],
            "yield_min" => is_array($yield) ? ($yield["min"] ?? null) : null,
            "yield_max" => is_array($yield) ? ($yield["max"] ?? null) : null,
            "maturity" => $agronomic["maturity"] ?? null,
            "wilt_disease" => $agronomic["wiltDisease"] ?? null,
            "downy_mildew" => $agronomic["downyMildew"] ?? null,
            "powdery_mildew" => $agronomic["powderyMildew"] ?? null,
            "aphid" => $agronomic["aphid"] ?? null,
            "substitutes" => $this->extractSubstitutes($data),
        ];
    }

    private function extractRange(?array $ingredientData): ?RangeOrNumber
    {
        if ($ingredientData === null) {
            return null;
        }

        $min = $ingredientData["min"] ?? null;
        $max = $ingredientData["max"] ?? null;

        if ($min === null && $max === null) {
            return null;
        }

        if ($min !== null && $max !== null) {
            return RangeOrNumber::fromRange((float)$min, (float)$max);
        }

        return RangeOrNumber::fromNumber((float)($min ?? $max));
    }

    private function extractSubstitutes(array $data): array
    {
        $alternatives = $data["ingredients"]["alternatives"] ?? [];

        return [
            "brewhouse" => $alternatives["brewhouse"] ?? [],
            "dryhopping" => $alternatives["dryhopping"] ?? [],
        ];
    }
}

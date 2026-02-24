<?php

declare(strict_types=1);

namespace HopsWeb\Actions;

class MapHopData
{
    public function execute(array $data): array
    {
        return [
            "name" => $data["name"] ?? null,
            "country" => $data["country"] ?? null,
            "description" => $data["origin"] ?? null,
            "alpha_acid_min" => $data["ingredients"]["alphas"]["min"] ?? null,
            "alpha_acid_max" => $data["ingredients"]["alphas"]["max"] ?? null,
            "beta_acid_min" => $data["ingredients"]["betas"]["min"] ?? null,
            "beta_acid_max" => $data["ingredients"]["betas"]["max"] ?? null,
            "cohumulone_min" => $data["ingredients"]["cohumulones"]["min"] ?? null,
            "cohumulone_max" => $data["ingredients"]["cohumulones"]["max"] ?? null,
            "total_oil_min" => $data["ingredients"]["oils"]["min"] ?? null,
            "total_oil_max" => $data["ingredients"]["oils"]["max"] ?? null,
            "polyphenol_min" => $data["ingredients"]["polyphenols"]["min"] ?? null,
            "polyphenol_max" => $data["ingredients"]["polyphenols"]["max"] ?? null,
            "xanthohumol_min" => $data["ingredients"]["xanthohumols"]["min"] ?? null,
            "xanthohumol_max" => $data["ingredients"]["xanthohumols"]["max"] ?? null,
            "farnesene_min" => $data["ingredients"]["farnesenes"]["min"] ?? null,
            "farnesene_max" => $data["ingredients"]["farnesenes"]["max"] ?? null,
            "linalool_min" => $data["ingredients"]["linalool"]["min"] ?? null,
            "linalool_max" => $data["ingredients"]["linalool"]["max"] ?? null,
            "aroma_citrusy" => $data["aroma"]["citrusy"] ?? null,
            "aroma_fruity" => $data["aroma"]["fruity"] ?? null,
            "aroma_floral" => $data["aroma"]["floral"] ?? null,
            "aroma_herbal" => $data["aroma"]["herbal"] ?? null,
            "aroma_spicy" => $data["aroma"]["spicy"] ?? null,
            "aroma_resinous" => $data["aroma"]["resinous"] ?? null,
            "aroma_sugarlike" => $data["aroma"]["sugarlike"] ?? null,
            "aroma_miscellaneous" => $data["aroma"]["misc"] ?? null,
            "aroma_descriptors" => $data["aromaDescription"] ?? [],
            "substitutes" => $this->extractSubstitutes($data),
        ];
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

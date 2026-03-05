<?php

declare(strict_types=1);

namespace Database\Factories;

use HopsWeb\Enums\Aromaticity;
use HopsWeb\Enums\Bitterness;
use HopsWeb\Enums\HopDescriptor;
use HopsWeb\Enums\HopLineage;
use HopsWeb\Enums\HopMaturity;
use HopsWeb\Enums\Resistance;
use HopsWeb\Models\Hop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hop>
 */
class HopFactory extends Factory
{
    public function definition(): array
    {
        $alphaMin = $this->faker->randomFloat(1, 2, 15);
        $betaMin = $this->faker->randomFloat(1, 1, 10);
        $cohumuloneMin = $this->faker->randomFloat(1, 10, 30);
        $oilMin = $this->faker->randomFloat(1, 0.5, 3.0);
        $polyphenolMin = $this->faker->randomFloat(1, 1, 5);
        $xanthohumolMin = $this->faker->randomFloat(1, 0.1, 1.0);
        $farneseneMin = $this->faker->randomFloat(1, 0.1, 10);
        $linaloolMin = $this->faker->randomFloat(1, 0.1, 1.5);
        $yieldMin = $this->faker->numberBetween(800, 2000);

        $substituteSlugs = fn(): array => $this->faker->randomElements(
            ["cascade", "centennial", "chinook", "citra", "mosaic", "simcoe", "amarillo"],
            $this->faker->numberBetween(0, 3),
        );

        return [
            "name" => $this->faker->unique()->word() . " Hop",
            "slug" => $this->faker->unique()->slug(),
            "alt_name" => $this->faker->optional()->word(),
            "country" => $this->faker->countryCode(),
            "description" => $this->faker->paragraph(),
            "descriptors" => $this->faker->randomElements(HopDescriptor::values(), 2),
            "lineage" => $this->faker->optional()->randomElements(HopLineage::values(), 2),
            "alpha_acid_min" => $alphaMin,
            "alpha_acid_max" => $alphaMin + $this->faker->randomFloat(1, 1, 5),
            "beta_acid_min" => $betaMin,
            "beta_acid_max" => $betaMin + $this->faker->randomFloat(1, 1, 4),
            "cohumulone_min" => $cohumuloneMin,
            "cohumulone_max" => $cohumuloneMin + $this->faker->randomFloat(1, 5, 15),
            "total_oil_min" => $oilMin,
            "total_oil_max" => $oilMin + $this->faker->randomFloat(1, 0.5, 2.0),
            "polyphenol_min" => $polyphenolMin,
            "polyphenol_max" => $polyphenolMin + $this->faker->randomFloat(1, 0.5, 2),
            "xanthohumol_min" => $xanthohumolMin,
            "xanthohumol_max" => $xanthohumolMin + $this->faker->randomFloat(1, 0.1, 0.5),
            "farnesene_min" => $farneseneMin,
            "farnesene_max" => $farneseneMin + $this->faker->randomFloat(1, 1, 5),
            "linalool_min" => $linaloolMin,
            "linalool_max" => $linaloolMin + $this->faker->randomFloat(1, 0.1, 0.5),
            "thiols" => $this->faker->randomElement(Aromaticity::values()),
            "aroma_citrusy" => $this->faker->numberBetween(0, 5),
            "aroma_fruity" => $this->faker->numberBetween(0, 5),
            "aroma_floral" => $this->faker->numberBetween(0, 5),
            "aroma_herbal" => $this->faker->numberBetween(0, 5),
            "aroma_spicy" => $this->faker->numberBetween(0, 5),
            "aroma_resinous" => $this->faker->numberBetween(0, 5),
            "aroma_sugarlike" => $this->faker->numberBetween(0, 5),
            "aroma_misc" => $this->faker->numberBetween(0, 5),
            "aroma_descriptors" => $this->faker->words(3),
            "substitutes" => [
                "brewhouse" => $substituteSlugs(),
                "dryhopping" => $substituteSlugs(),
            ],
            "yield_min" => $yieldMin,
            "yield_max" => $yieldMin + $this->faker->numberBetween(100, 500),
            "maturity" => $this->faker->optional()->randomElement(HopMaturity::values()),
            "wilt_disease" => $this->faker->optional()->randomElement(Resistance::values()),
            "downy_mildew" => $this->faker->optional()->randomElement(Resistance::values()),
            "powdery_mildew" => $this->faker->optional()->randomElement(Resistance::values()),
            "aphid" => $this->faker->optional()->randomElement(Resistance::values()),
            "bitterness" => $this->faker->optional()->randomElement(Bitterness::values()),
            "aromaticity" => $this->faker->optional()->randomElement(Aromaticity::values()),
        ];
    }
}

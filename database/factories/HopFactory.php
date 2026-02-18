<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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

        return [
            "name" => $this->faker->unique()->word() . " Hop",
            "slug" => $this->faker->unique()->slug(),
            "country" => $this->faker->country(),
            "description" => $this->faker->paragraph(),
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
            "aroma_citrusy" => $this->faker->randomFloat(1, 0, 5),
            "aroma_fruity" => $this->faker->randomFloat(1, 0, 5),
            "aroma_floral" => $this->faker->randomFloat(1, 0, 5),
            "aroma_herbal" => $this->faker->randomFloat(1, 0, 5),
            "aroma_spicy" => $this->faker->randomFloat(1, 0, 5),
            "aroma_resinous" => $this->faker->randomFloat(1, 0, 5),
            "aroma_sugarlike" => $this->faker->randomFloat(1, 0, 5),
            "aroma_miscellaneous" => $this->faker->randomFloat(1, 0, 5),
            "aroma_descriptors" => $this->faker->words(3),
            "substitutes" => $this->faker->words(3),
        ];
    }
}

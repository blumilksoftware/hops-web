<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum BiochemicalProperty: string
{
    use EnumValues;

    case AlphaAcid = "alpha_acid";
    case BetaAcid = "beta_acid";
    case Cohumulone = "cohumulone";
    case TotalOil = "total_oil";
    case Polyphenol = "polyphenol";
    case Xanthohumol = "xanthohumol";
    case Farnesene = "farnesene";
    case Linalool = "linalool";

    public function label(): string
    {
        return match ($this) {
            self::AlphaAcid => __("Alpha Acid (%)"),
            self::BetaAcid => __("Beta Acid (%)"),
            self::Cohumulone => __("Cohumulone (%)"),
            self::TotalOil => __("Total Oil (ml/100g)"),
            self::Polyphenol => __("Polyphenols (%)"),
            self::Xanthohumol => __("Xanthohumol (%)"),
            self::Farnesene => __("Farnesene (%)"),
            self::Linalool => __("Linalool (%)"),
        };
    }
}

<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum HopBiochemical: string
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
            self::AlphaAcid => "Alpha Acid",
            self::BetaAcid => "Beta Acid",
            self::TotalOil => "Total Oil",
            default => $this->value,
        };
    }
}

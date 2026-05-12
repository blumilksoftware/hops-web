<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

enum AromaProfile: string
{
    case Citrusy = "aroma_citrusy";
    case Fruity = "aroma_fruity";
    case Floral = "aroma_floral";
    case Herbal = "aroma_herbal";
    case Spicy = "aroma_spicy";
    case Resinous = "aroma_resinous";
    case Sugarlike = "aroma_sugarlike";
    case Miscellaneous = "aroma_misc";

    public function label(): string
    {
        return match($this) {
            self::Citrusy => "Citrusy",
            self::Fruity => "Fruity",
            self::Floral => "Floral",
            self::Herbal => "Herbal",
            self::Spicy => "Spicy",
            self::Resinous => "Resinous",
            self::Sugarlike => "Sweet/Sugarlike",
            self::Miscellaneous => "Miscellaneous",
        };
    }
}

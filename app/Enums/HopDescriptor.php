<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum HopDescriptor: string
{
    use EnumValues;

    case Fruity = "fruity";
    case Citrusy = "citrusy";
    case Herbal = "herbal";
    case Spicy = "spicy";
    case Floral = "floral";
    case Resinous = "resinous";
}

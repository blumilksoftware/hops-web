<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum HopLineage: string
{
    use EnumValues;

    case BrewersGold = "brewers-gold";
    case Fuggle = "fuggle";
    case Cascade = "cascade";
}

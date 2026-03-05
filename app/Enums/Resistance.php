<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum Resistance: string
{
    use EnumValues;

    case Resistant = "resistant";
    case Tolerant = "tolerant";
    case Susceptible = "susceptible";
}

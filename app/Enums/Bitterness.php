<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum Bitterness: string
{
    use EnumValues;

    case Low = "low";
    case Medium = "medium";
    case High = "high";
}

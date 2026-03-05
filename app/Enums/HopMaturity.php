<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

use HopsWeb\Traits\EnumValues;

enum HopMaturity: string
{
    use EnumValues;

    case Early = "early";
    case MidEarly = "mid early";
    case MidLate = "mid late";
    case Late = "late";
    case VeryLate = "very late";
}

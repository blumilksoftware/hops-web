<?php

declare(strict_types=1);

namespace HopsWeb\Enums;

enum ComparisonModule: string
{
    case Aroma = "aroma";
    case Biochemical = "biochemical";
    case Description = "description";
    case Feeling = "feeling";

    public function label(): string
    {
        return match ($this) {
            self::Aroma => __("Aroma"),
            self::Biochemical => __("Biochemical"),
            self::Description => __("Description"),
            self::Feeling => __("Feeling"),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Aroma => __("Sensory aroma profile matching"),
            self::Biochemical => __("Chemical composition properties"),
            self::Description => __("Textual descriptor similarity"),
            self::Feeling => __("Bitterness and aromaticity profile"),
        };
    }
}

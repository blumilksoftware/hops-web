<?php

declare(strict_types=1);

namespace HopsWeb\Casts;

use HopsWeb\ValueObjects\RangeOrNumber;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class RangeOrNumberCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?RangeOrNumber
    {
        $min = $attributes["{$key}_min"] ?? null;
        $max = $attributes["{$key}_max"] ?? null;

        if ($min === null && $max === null) {
            return null;
        }

        if ($min === $max) {
            return RangeOrNumber::fromNumber((float)$min);
        }

        return RangeOrNumber::fromRange((float)$min, (float)$max);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [
                "{$key}_min" => null,
                "{$key}_max" => null,
            ];
        }

        if (!$value instanceof RangeOrNumber) {
            throw new InvalidArgumentException("Value must be an instance of RangeOrNumber or null.");
        }

        if ($value->isNumber()) {
            return [
                "{$key}_min" => $value->exact,
                "{$key}_max" => $value->exact,
            ];
        }

        return [
            "{$key}_min" => $value->min,
            "{$key}_max" => $value->max,
        ];
    }
}

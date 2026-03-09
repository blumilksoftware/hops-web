<?php

declare(strict_types=1);

namespace HopsWeb\ValueObjects;

use InvalidArgumentException;

class RangeOrNumber
{
    public function __construct(
        public readonly ?float $min,
        public readonly ?float $max,
        public readonly ?float $exact,
    ) {
        if ($min !== null && $max !== null && $min > $max) {
            throw new InvalidArgumentException("Min value must be less than or equal to max value");
        }

        if ($exact !== null && $exact < 0) {
            throw new InvalidArgumentException("Exact value must be greater than or equal to 0");
        }
    }

    public static function fromNumber(float $value): self
    {
        return new self(null, null, $value);
    }

    public static function fromRange(float $min, float $max): self
    {
        return new self($min, $max, null);
    }

    public function isRange(): bool
    {
        return $this->exact === null;
    }

    public function isNumber(): bool
    {
        return $this->exact !== null;
    }
}

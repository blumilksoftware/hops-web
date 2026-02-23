<?php

declare(strict_types=1);

namespace HopsWeb\ValueObjects;

use InvalidArgumentException;

final class Range
{
    public function __construct(
        public readonly ?float $min,
        public readonly ?float $max,
    ) {
        if ($min !== null && $max !== null && $min > $max) {
            throw new InvalidArgumentException("min cannot be greater than max");
        }
    }

    public static function exact(float $value): self
    {
        return new self(min: $value, max: $value);
    }

    public static function from(float $min, float $max): self
    {
        return new self($min, $max);
    }

    public static function atLeast(float $min): self
    {
        return new self($min, null);
    }

    public static function atMost(float $max): self
    {
        return new self(null, $max);
    }

    public function isExact(): bool
    {
        return $this->min === $this->max && $this->min !== null;
    }

    public function contains(float $value): bool
    {
        if ($this->min !== null && $value < $this->min) {
            return false;
        }

        if ($this->max !== null && $value > $this->max) {
            return false;
        }

        return true;
    }
}

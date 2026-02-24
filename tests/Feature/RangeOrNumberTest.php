<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\ValueObjects\RangeOrNumber;
use InvalidArgumentException;
use Tests\TestCase;

class RangeOrNumberTest extends TestCase
{
    public function testRangeCanBeCreatedWithMinAndMax(): void
    {
        $range = RangeOrNumber::fromRange(1, 10);

        $this->assertEquals(1, $range->min);
        $this->assertEquals(10, $range->max);
    }

    public function testRangeCanBeCreatedWithExactValue(): void
    {
        $range = RangeOrNumber::fromNumber(1);

        $this->assertEquals(1, $range->exact);
    }

    public function testRangeThrowsExceptionWhenMinIsGreaterThanMax(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RangeOrNumber::fromRange(10, 1);
    }

    public function testRangeThrowsExceptionWhenExactValueIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RangeOrNumber::fromNumber(-1);
    }
}

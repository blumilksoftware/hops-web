<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\ValueObjects\Range;
use InvalidArgumentException;
use Tests\TestCase;

class RangeTest extends TestCase
{
    public function testRangeCanBeCreatedWithMinAndMax(): void
    {
        $range = Range::fromRange(1, 10);

        $this->assertEquals(1, $range->min);
        $this->assertEquals(10, $range->max);
    }

    public function testRangeCanBeCreatedWithExactValue(): void
    {
        $range = Range::fromNumber(1);

        $this->assertEquals(1, $range->exact);
    }

    public function testRangeThrowsExceptionWhenMinIsGreaterThanMax(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Range::fromRange(10, 1);
    }

    public function testRangeThrowsExceptionWhenExactValueIsNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Range::fromNumber(-1);
    }
}

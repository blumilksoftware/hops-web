<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\ValueObjects\Range;
use Tests\TestCase;

class RangeTest extends TestCase
{
    public function testRangeCanBeCreatedWithMinAndMax(): void
    {
        $range = Range::from(1, 10);

        $this->assertEquals(1, $range->min);
        $this->assertEquals(10, $range->max);
    }

    public function testRangeCanBeCreatedWithExactValue(): void
    {
        $range = Range::exact(5);

        $this->assertEquals(5, $range->min);
        $this->assertEquals(5, $range->max);
    }

    public function testRangeCanBeCreatedWithMinOnly(): void
    {
        $range = Range::atLeast(5);

        $this->assertEquals(5, $range->min);
        $this->assertNull($range->max);
    }

    public function testRangeCanBeCreatedWithMaxOnly(): void
    {
        $range = Range::atMost(5);

        $this->assertNull($range->min);
        $this->assertEquals(5, $range->max);
    }

    public function testRangeThrowsExceptionWhenMinIsGreaterThanMax(): void
    {
        $this->expectException("InvalidArgumentException");

        Range::from(10, 1);
    }

    public function testRangeContainsValue(): void
    {
        $range = Range::from(1, 10);

        $this->assertTrue($range->contains(5));
    }

    public function testRangeDoesNotContainValue(): void
    {
        $range = Range::from(1, 10);

        $this->assertFalse($range->contains(11));
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature;

use HopsWeb\Models\Hop;
use HopsWeb\ValueObjects\RangeOrNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HopBrowserTest extends TestCase
{
    use RefreshDatabase;

    public function testItDisplaysHopList(): void
    {
        Hop::factory()->create(["name" => "Citra", "slug" => "citra"]);
        Hop::factory()->create(["name" => "Mosaic", "slug" => "mosaic"]);

        $response = $this->get("/hops");

        $response->assertStatus(200);
        $response->assertSee("Citra");
        $response->assertSee("Mosaic");
    }

    public function testItDisplaysHopDetails(): void
    {
        $hop = Hop::factory()->create(["name" => "Citra", "slug" => "citra"]);

        $response = $this->get("/hops/{$hop->slug}");

        $response->assertStatus(200);
        $response->assertSee("Citra");
        $response->assertSee("Biochemical Profile");
    }

    public function testItFiltersByAroma(): void
    {
        Hop::factory()->create(["name" => "Citrusy Hop", "slug" => "citrusy-hop", "aroma_citrusy" => 1]);
        Hop::factory()->create(["name" => "Fruity Hop", "slug" => "fruity-hop", "aroma_citrusy" => 0]);

        $response = $this->get("/hops?aroma_citrusy=1");

        $response->assertStatus(200);
        $response->assertSee("Citrusy Hop");
        $response->assertDontSee("Fruity Hop");
    }

    public function testItFiltersByAlphaAcidRange(): void
    {
        Hop::factory()->create([
            "name" => "High Alpha",
            "slug" => "high-alpha",
            "alpha_acid" => RangeOrNumber::fromRange(10, 12),
        ]);

        Hop::factory()->create([
            "name" => "Low Alpha",
            "slug" => "low-alpha",
            "alpha_acid" => RangeOrNumber::fromRange(4, 6),
        ]);

        $response = $this->get("/hops?alpha_acid_min=8");

        $response->assertStatus(200);
        $response->assertSee("High Alpha");
        $response->assertDontSee("Low Alpha");
    }
}

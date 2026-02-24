<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use HopsWeb\Jobs\ImportHopVarietyJob;
use HopsWeb\Models\Hop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportHopVarietyJobTest extends TestCase
{
    use RefreshDatabase;

    public function testItImportsHopFromJson5File(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/cascade.json5", $this->sampleJson5());

        ImportHopVarietyJob::dispatchSync("hops_data/cascade.json5");

        $this->assertDatabaseHas("hops", [
            "name" => "Cascade",
            "slug" => "cascade-hop",
            "country" => "US",
        ]);

        $hop = Hop::where("name", "Cascade")->first();
        $this->assertNotNull($hop);
        $this->assertEquals("A very popular aroma hop.", $hop->description);
        $this->assertEquals(3, $hop->aroma_citrusy);
        $this->assertEquals(3, $hop->aroma_fruity);
        $this->assertEquals(1, $hop->aroma_floral);
        $this->assertEquals(0, $hop->aroma_spicy);
        $this->assertEquals(["lime", "black currant"], $hop->aroma_descriptors);
        $this->assertEquals(["centennial", "lemondrop"], $hop->substitutes);
        $this->assertNotNull($hop->alpha_acid);
        $this->assertEquals(4.5, $hop->alpha_acid->min);
        $this->assertEquals(7.0, $hop->alpha_acid->max);
        $this->assertNull($hop->polyphenol);
    }

    public function testItUpdatesExistingHopOnReimport(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/cascade.json5", $this->sampleJson5());

        ImportHopVarietyJob::dispatchSync("hops_data/cascade.json5");
        ImportHopVarietyJob::dispatchSync("hops_data/cascade.json5");

        $this->assertDatabaseCount("hops", 1);
    }

    public function testItLogsWarningForMissingFile(): void
    {
        Storage::fake("local");
        Log::shouldReceive("warning")
            ->once()
            ->withArgs(fn(string $msg) => str_contains($msg, "File not found"));

        ImportHopVarietyJob::dispatchSync("hops_data/nonexistent.json5");

        $this->assertDatabaseCount("hops", 0);
    }

    public function testItLogsWarningForInvalidJson(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/bad.json5", "not valid json at all {{{{");

        Log::shouldReceive("warning")
            ->once()
            ->withArgs(fn(string $msg) => str_contains($msg, "Failed to parse"));

        ImportHopVarietyJob::dispatchSync("hops_data/bad.json5");

        $this->assertDatabaseCount("hops", 0);
    }

    public function testItLogsWarningForValidationFailure(): void
    {
        Storage::fake("local");
        // Missing required 'name' field
        Storage::disk("local")->put("hops_data/invalid.json5", '{ country: "US" }');

        Log::shouldReceive("warning")
            ->once()
            ->withArgs(fn(string $msg) => str_contains($msg, "Validation failed"));

        ImportHopVarietyJob::dispatchSync("hops_data/invalid.json5");

        $this->assertDatabaseCount("hops", 0);
    }

    public function testItHandlesNullIngredientRanges(): void
    {
        Storage::fake("local");
        $json5 = <<<'JSON5'
        {
          name: "Test Hop",
          country: "DE",
          aroma: {
            citrusy: 1,
            fruity: 0,
            floral: 0,
            herbal: 0,
            spicy: 0,
            resinous: 0,
            sugarlike: 0,
            misc: 0
          },
          aromaDescription: [],
          ingredients: {
            alphas: { min: 5.0, max: 8.0 },
            betas: null,
            cohumulones: null,
            polyphenols: null,
            xanthohumols: null,
            oils: null,
            farnesenes: null,
            linalool: null,
            alternatives: {
              brewhouse: [],
              dryhopping: []
            }
          }
        }
        JSON5;

        Storage::disk("local")->put("hops_data/test.json5", $json5);

        ImportHopVarietyJob::dispatchSync("hops_data/test.json5");

        $hop = Hop::where("name", "Test Hop")->first();
        $this->assertNotNull($hop);
        $this->assertNotNull($hop->alpha_acid);
        $this->assertNull($hop->beta_acid);
        $this->assertNull($hop->polyphenol);
    }

    private function sampleJson5(): string
    {
        return <<<'JSON5'
        {
          id: "cascade",
          name: "Cascade",
          altName: null,
          country: "US",
          descriptors: ["citrusy", "fruity", "herbal"],
          origin: "A very popular aroma hop.",
          aroma: {
            citrusy: 3,
            fruity: 3,
            floral: 1,
            herbal: 3,
            spicy: 0,
            resinous: 1,
            sugarlike: 0,
            misc: 0
          },
          aromaDescription: ["lime", "black currant"],
          agronomic: {
            yield: { min: 1600, max: 2200 },
            maturity: "early to mid early"
          },
          ingredients: {
            alphas: { min: 4.5, max: 7.0 },
            betas: { min: 4.5, max: 7.0 },
            cohumulones: { min: 33, max: 40 },
            polyphenols: null,
            xanthohumols: { min: 0.1, max: 0.4 },
            oils: { min: 0.8, max: 1.5 },
            farnesenes: { min: 4.0, max: 8.0 },
            linalool: { min: 0.4, max: 0.6 },
            thiols: "high",
            alternatives: {
              brewhouse: ["centennial", "lemondrop"],
              dryhopping: ["centennial", "lemondrop"]
            }
          }
        }
        JSON5;
    }
}

<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs;

use HopsWeb\Jobs\ImportHopVarietyJob;
use HopsWeb\Models\Hop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\Helpers\HopFixture;
use Tests\TestCase;

class ImportHopVarietyJobTest extends TestCase
{
    use RefreshDatabase;

    public function testItImportsHopFromJson5File(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/cascade.json5", HopFixture::cascade());

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
        $this->assertEquals(0, $hop->aroma_misc);
        $this->assertEquals(["lime", "black currant"], $hop->aroma_descriptors);
        $this->assertEquals(["brewhouse" => ["centennial", "lemondrop"], "dryhopping" => ["centennial", "lemondrop"]], $hop->substitutes);
        $this->assertNotNull($hop->alpha_acid);
        $this->assertEquals(4.5, $hop->alpha_acid->min);
        $this->assertEquals(7.0, $hop->alpha_acid->max);
        $this->assertNull($hop->polyphenol);
    }

    public function testItUpdatesExistingHopOnReimport(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/cascade.json5", HopFixture::cascade());

        ImportHopVarietyJob::dispatchSync("hops_data/cascade.json5");
        ImportHopVarietyJob::dispatchSync("hops_data/cascade.json5");

        $this->assertDatabaseCount("hops", 1);
    }

    public function testItLogsWarningForMissingFile(): void
    {
        Storage::fake("local");
        Log::shouldReceive("warning")
            ->once()
            ->withArgs(fn(string $msg): bool => Str::contains($msg, "File not found"));

        ImportHopVarietyJob::dispatchSync("hops_data/nonexistent.json5");

        $this->assertDatabaseCount("hops", 0);
    }

    public function testItLogsWarningForInvalidJson(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/bad.json5", "not valid json at all {{{{");

        Log::shouldReceive("warning")
            ->once()
            ->withArgs(fn(string $msg): bool => Str::contains($msg, "Failed to parse"));

        ImportHopVarietyJob::dispatchSync("hops_data/bad.json5");

        $this->assertDatabaseCount("hops", 0);
    }

    public function testItLogsWarningForValidationFailure(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/invalid.json5", '{ country: "US" }');

        Log::shouldReceive("warning")
            ->once()
            ->withArgs(fn(string $msg): bool => Str::contains($msg, "Validation failed"));

        ImportHopVarietyJob::dispatchSync("hops_data/invalid.json5");

        $this->assertDatabaseCount("hops", 0);
    }

    public function testItHandlesNullIngredientRanges(): void
    {
        Storage::fake("local");
        Storage::disk("local")->put("hops_data/minimal.json5", HopFixture::minimal());

        ImportHopVarietyJob::dispatchSync("hops_data/minimal.json5");

        $hop = Hop::where("name", "Test Hop")->first();
        $this->assertNotNull($hop);
        $this->assertNotNull($hop->alpha_acid);
        $this->assertNull($hop->beta_acid);
        $this->assertNull($hop->polyphenol);
    }
}

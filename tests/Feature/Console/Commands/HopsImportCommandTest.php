<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use HopsWeb\Jobs\ImportHopVarietyJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HopsImportCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testItDispatchesImportJobsForJson5Files(): void
    {
        Storage::fake("local");
        Bus::fake();

        Storage::disk("local")->put("hops_data/citra.json5", "{}");
        Storage::disk("local")->put("hops_data/mosaic.json5", "{}");
        Storage::disk("local")->put("hops_data/not-a-hop.txt", "test");

        $this->artisan("hops:import")
            ->assertExitCode(0);

        Bus::assertDispatched(ImportHopVarietyJob::class, fn(ImportHopVarietyJob $job): bool => $job->filePath === "hops_data/citra.json5");
        Bus::assertDispatched(ImportHopVarietyJob::class, fn(ImportHopVarietyJob $job): bool => $job->filePath === "hops_data/mosaic.json5");
        Bus::assertNotDispatched(ImportHopVarietyJob::class, fn(ImportHopVarietyJob $job): bool => $job->filePath === "hops_data/not-a-hop.txt");
    }

    public function testItAlsoDispatchesForPlainJsonFiles(): void
    {
        Storage::fake("local");
        Bus::fake();

        Storage::disk("local")->put("hops_data/citra.json", "{}");

        $this->artisan("hops:import")
            ->assertExitCode(0);

        Bus::assertDispatched(ImportHopVarietyJob::class, 1);
    }

    public function testItShowsMessageWhenNoFilesFound(): void
    {
        Storage::fake("local");

        $this->artisan("hops:import")
            ->assertExitCode(0);
    }

    public function testItAcceptsCustomFolderArgument(): void
    {
        Storage::fake("local");
        Bus::fake();

        Storage::disk("local")->put("custom_folder/galaxy.json5", "{}");

        $this->artisan("hops:import", ["folder" => "custom_folder"])
            ->assertExitCode(0);

        Bus::assertDispatched(ImportHopVarietyJob::class, fn(ImportHopVarietyJob $job): bool => $job->filePath === "custom_folder/galaxy.json5");
    }
}

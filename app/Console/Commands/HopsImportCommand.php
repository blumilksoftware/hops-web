<?php

declare(strict_types=1);

namespace HopsWeb\Console\Commands;

use HopsWeb\Jobs\ImportHopVarietyJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class HopsImportCommand extends Command
{
    protected $signature = "hops:import";
    protected $description = "Import hop varieties from JSON/JSON5 files in storage";

    public function handle(): void
    {
        $files = collect(Storage::disk("local")->files("hops_data"))
            ->filter(fn(string $file): bool => in_array(
                pathinfo($file, PATHINFO_EXTENSION),
                ["json", "json5"],
                true,
            ));

        if ($files->isEmpty()) {
            $this->info("No files found in hops_data directory.");

            return;
        }

        $this->info("Found {$files->count()} file(s) to import.");

        foreach ($files as $file) {
            $this->info("Dispatching import for: {$file}");
            ImportHopVarietyJob::dispatch($file);
        }

        $this->info("All import jobs have been dispatched.");
    }
}

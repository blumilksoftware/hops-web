<?php

declare(strict_types=1);

namespace HopsWeb\Console\Commands;

use HopsWeb\Jobs\ImportHopVarietyJob;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemManager;

class HopsImportCommand extends Command
{
    protected $signature = "hops:import {folder=hops_data : Directory inside storage to scan for JSON/JSON5 files}";
    protected $description = "Import hop varieties from JSON/JSON5 files in storage";

    public function handle(FilesystemManager $filesystem): void
    {
        $folder = $this->argument("folder");

        $files = collect($filesystem->files($folder))
            ->filter(fn(string $file): bool => in_array(
                pathinfo($file, PATHINFO_EXTENSION),
                ["json", "json5"],
                true,
            ));

        if ($files->isEmpty()) {
            $this->info("No files found in {$folder} directory.");

            return;
        }

        $this->info("Found {$files->count()} hop variety file(s) to import.");

        foreach ($files as $file) {
            $this->info("Dispatching import for: {$file}");
            ImportHopVarietyJob::dispatch($file);
        }
    }
}

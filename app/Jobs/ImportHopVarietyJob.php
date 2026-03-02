<?php

declare(strict_types=1);

namespace HopsWeb\Jobs;

use HopsWeb\Actions\MapHopData;
use HopsWeb\Actions\UpsertHop;
use HopsWeb\Helpers\Json5Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ImportHopVarietyJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $filePath,
    ) {}

    public function handle(Json5Parser $parser, MapHopData $mapper, UpsertHop $upsert, FilesystemManager $filesystem): void
    {
        $content = $filesystem->get($this->filePath);

        if ($content === null) {
            Log::warning("ImportHopVarietyJob: File not found: {$this->filePath}");

            return;
        }

        $data = $parser->parse($content);

        if ($data === null) {
            Log::warning("ImportHopVarietyJob: Failed to parse JSON5: {$this->filePath}");

            return;
        }

        $mapped = $mapper->execute($data);

        try {
            $upsert->execute($mapped);
        } catch (ValidationException $e) {
            Log::warning("ImportHopVarietyJob: Validation failed for {$this->filePath}", [
                "errors" => $e->errors(),
            ]);
        }
    }
}

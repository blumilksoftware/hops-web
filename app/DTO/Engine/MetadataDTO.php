<?php

declare(strict_types=1);

namespace HopsWeb\DTO\Engine;

readonly class MetadataDTO
{
    /**
     * @param array<string> $modulesUsed
     */
    public function __construct(
        public array $modulesUsed,
        public int $executionTimeMs,
    ) {}
}

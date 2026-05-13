<?php

declare(strict_types=1);

namespace HopsWeb\DTO\Engine;

readonly class ComparisonResponseDTO
{
    /**
     * @param array<HopResultDTO> $results
     */
    public function __construct(
        public array $results,
        public MetadataDTO $metadata,
    ) {}
}

<?php

declare(strict_types=1);

namespace HopsWeb\DTO\Engine;

readonly class HopResultDTO
{
    /**
     * @param array<string, string> $explainability
     */
    public function __construct(
        public string $hopId,
        public float $similarityScore,
        public array $explainability,
    ) {}
}

<?php

declare(strict_types=1);

namespace HopsWeb\DTO\Engine;

readonly class HopResultDTO
{
    public function __construct(
        public string $hopId,
        public float $similarityScore,
        public array $explainability,
    ) {}
}

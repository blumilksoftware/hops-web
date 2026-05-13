<?php

declare(strict_types=1);

namespace HopsWeb\Services\ComparisonEngine;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;
use HopsWeb\DTO\Engine\ComparisonResponseDTO;
use HopsWeb\DTO\Engine\HopResultDTO;
use HopsWeb\DTO\Engine\MetadataDTO;

class FakeComparisonEngineClient implements ComparisonEngineClientInterface
{
    public function compare(ComparisonQueryDTO $query): ComparisonResponseDTO
    {
        return new ComparisonResponseDTO(
            results: [
                new HopResultDTO(
                    hopId: "cascade",
                    similarityScore: 0.95,
                    explainability: [
                        "aroma" => "High match on citrus notes",
                        "feeling" => "Exact match on bitterness",
                    ],
                ),
                new HopResultDTO(
                    hopId: "centennial",
                    similarityScore: 0.88,
                    explainability: [
                        "aroma" => "Partial match on floral notes",
                    ],
                ),
                new HopResultDTO(
                    hopId: "columbus",
                    similarityScore: 0.76,
                    explainability: [
                        "feeling" => "Match on high bitterness",
                    ],
                ),
            ],
            metadata: new MetadataDTO(
                modulesUsed: array_keys($query->toArray()),
                executionTimeMs: 120,
            ),
        );
    }
}

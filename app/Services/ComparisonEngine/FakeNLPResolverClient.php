<?php

declare(strict_types=1);

namespace HopsWeb\Services\ComparisonEngine;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;

class FakeNLPResolverClient implements NLPResolverClientInterface
{
    public function resolve(string $naturalLanguageQuery): ComparisonQueryDTO
    {
        return new ComparisonQueryDTO(
            target: [
                "present" => ["Citra"],
            ],
            aroma: [
                "present" => ["citrus", "tropical_fruit"],
            ],
        );
    }
}

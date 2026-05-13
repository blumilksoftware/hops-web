<?php

declare(strict_types=1);

namespace HopsWeb\Services;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;
use HopsWeb\DTO\Engine\ComparisonResponseDTO;
use HopsWeb\Services\ComparisonEngine\ComparisonEngineClientInterface;

class ComparisonService
{
    public function __construct(
        private readonly ComparisonEngineClientInterface $client,
    ) {}

    public function compare(ComparisonQueryDTO $query): ComparisonResponseDTO
    {
        return $this->client->compare($query);
    }
}

<?php

declare(strict_types=1);

namespace HopsWeb\Services\ComparisonEngine;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;
use HopsWeb\DTO\Engine\ComparisonResponseDTO;

interface ComparisonEngineClientInterface
{
    public function compare(ComparisonQueryDTO $query): ComparisonResponseDTO;
}

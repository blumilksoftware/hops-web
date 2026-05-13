<?php

declare(strict_types=1);

namespace HopsWeb\Services\ComparisonEngine;

use HopsWeb\DTO\Engine\ComparisonQueryDTO;

interface NLPResolverClientInterface
{
    public function resolve(string $naturalLanguageQuery): ComparisonQueryDTO;
}

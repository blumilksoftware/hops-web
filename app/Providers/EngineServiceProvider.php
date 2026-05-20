<?php

declare(strict_types=1);

namespace HopsWeb\Providers;

use HopsWeb\Services\ComparisonEngine\ComparisonEngineClientInterface;
use HopsWeb\Services\ComparisonEngine\FakeComparisonEngineClient;
use HopsWeb\Services\ComparisonEngine\FakeNLPResolverClient;
use HopsWeb\Services\ComparisonEngine\NLPResolverClientInterface;
use Illuminate\Support\ServiceProvider;

class EngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ComparisonEngineClientInterface::class, FakeComparisonEngineClient::class);
        $this->app->bind(NLPResolverClientInterface::class, FakeNLPResolverClient::class);
    }
}

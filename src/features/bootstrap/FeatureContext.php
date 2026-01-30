<?php

use Behat\Behat\Context\Context;

class FeatureContext implements Context
        {
            use \Blumilk\BLT\Features\Traits\Http;
            use \Blumilk\BLT\Features\Traits\Eloquent;

            public function __construct()
            {
                $bootstrapper = new \Blumilk\BLT\Bootstrapping\LaravelBootstrapper();
                $bootstrapper->setBasePath(realpath(__DIR__ . "/../.."));
                $bootstrapper->boot();
            }
        }

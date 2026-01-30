<?php

use Behat\Behat\Context\Context;
use Blumilk\BLT\Features\Toolbox;
use Blumilk\BLT\Bootstrapping\LaravelBootstrapper;

class FeatureContext extends Toolbox implements Context
        {
            public function __construct()
            {
                $bootstrapper = new LaravelBootstrapper();
                $bootstrapper->setBasePath(realpath(__DIR__ . "/../.."));
                $bootstrapper->boot();
            }
        }

<?php

namespace Adrianorosa\GeoLocation\Tests;

use Adrianorosa\GeoLocation\GeoLocation;
use Adrianorosa\GeoLocation\GeoLocationServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [GeoLocationServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'GeoLocation' => GeoLocation::class
        ];
    }
}

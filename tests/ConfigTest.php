<?php

namespace Adrianorosa\GeoLocation\Tests;

/**
 * Class ConfigTest.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 20:36
 *
 * @package Adrianorosa\GeoLocation\Tests
 */
class ConfigTest extends TestCase
{
    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationServiceProvider::boot
     */
    public function testGetConfigValues()
    {
        $this->assertNotNull(config('geolocation.drivers.default'));
        $this->assertNotNull(config('geolocation.providers.ipinfo.access_token'));
        $this->assertNotNull(config('geolocation.cache.ttl'));

        $this->assertEquals(4096, config('geolocation.cache.ttl'));
        $this->assertEquals('LftDDkrDdJtxhA', config('geolocation.providers.ipinfo.access_token'));
    }

}

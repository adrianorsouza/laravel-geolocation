<?php

namespace Adrianorosa\GeoLocation\Tests;

use Adrianorosa\GeoLocation\Providers\IpInfo;
use Adrianorosa\GeoLocation\GeoLocationManager;

class GeoLocationManagerTest extends TestCase
{
    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationManager::resolve()
     */
    public function testBindManagerInvalidArgumentException()
    {
        $this->expectExceptionMessage('GeoLocation Driver [foo] is not defined.');
        new GeoLocationManager([
            'drivers' => [
                'default' => 'foo'
            ]
        ], $this->app->get('cache'));
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationManager::resolve()
     */
    public function testBindManagerMethodNotSupportedException()
    {
        $this->expectExceptionMessage('GeoLocation Driver [bar] is not supported.');
        new GeoLocationManager([
            'drivers' => [
                'default' => 'ipinfo'
            ],
            'providers' => [
                'ipinfo' => [
                    'driver' => 'bar'
                ]
            ]
        ], $this->app->get('cache'));
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationManager::resolve()
     */
    public function testCreateUndefinedProvider()
    {
        $this->expectExceptionMessage('GeoLocation Driver [bar] is not defined.');
        $manager = new GeoLocationManager(config('geolocation'), $this->app->get('cache'));
        $manager->driver('bar');
    }

    /**
     * @covers GeoLocationManager::createIpinfoDriver()
     */
    public function testCreateIpInfoProvider()
    {
        $manager = new GeoLocationManager(config('geolocation'), $this->app->get('cache'));
        $this->assertInstanceOf(IpInfo::class, $manager->driver('ipinfo'));
    }
}

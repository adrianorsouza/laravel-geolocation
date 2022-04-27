<?php

namespace Adrianorosa\GeoLocation\Tests;

use Adrianorosa\GeoLocation\Providers\IpInfo;
use Adrianorosa\GeoLocation\GeoLocationManager;

class GeoLocationManagerTest extends TestCase
{
    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationManager::resolve()
     */
    public function testBindManagerInvalidArgumentException() : void
    {
        $this->expectExceptionMessage('GeoLocation Driver [foo] is not defined.');

        $config = [
            'drivers' => [
                'default' => 'foo'
            ]
        ];

        new GeoLocationManager($config, $this->app->get('cache'));
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationManager::resolve()
     */
    public function testBindManagerMethodNotSupportedException() : void
    {
        $this->expectExceptionMessage('GeoLocation Driver [bar] is not supported.');

        $config = [
            'drivers' => [
                'default' => 'ipinfo'
            ],
            'providers' => [
                'ipinfo' => [
                    'driver' => 'bar'
                ]
            ]
        ];

        new GeoLocationManager($config, $this->app->get('cache'));
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocationManager::resolve()
     */
    public function testCreateUndefinedProvider() : void
    {
        $this->expectExceptionMessage('GeoLocation Driver [bar] is not defined.');
        $manager = new GeoLocationManager(config('geolocation'), $this->app->get('cache'));
        $manager->driver('bar');
    }

    /**
     * @covers GeoLocationManager::createIpinfoDriver()
     */
    public function testCreateIpInfoProvider() : void
    {
        $manager = new GeoLocationManager(config('geolocation'), $this->app->get('cache'));
        $this->assertInstanceOf(IpInfo::class, $manager->driver('ipinfo'));
    }
}

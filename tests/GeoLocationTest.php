<?php

namespace Adrianorosa\GeoLocation\Tests;

use Adrianorosa\GeoLocation\GeoLocation;
use Adrianorosa\GeoLocation\GeoLocationDetails;
use Adrianorosa\GeoLocation\GeoLocationException;

class GeoLocationTest extends TestCase
{
    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation
     */
    public function testLoadConfig()
    {
        $this->assertNotNull($this->app['config']['geolocation']);
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation
     */
    public function testProviderBoundInstance()
    {
        $this->assertTrue($this->app->bound('geolocation'));
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation
     */
    public function testProvider()
    {
        $this->assertTrue($this->app->bound('geolocation'));
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation
     */
    public function testGeoLocation()
    {
        /**@var \Illuminate\Cache\ArrayStore $cache*/
        $cache = $this->app->get('cache')->getStore();
        $ipAddress = '8.8.8.8';
        $data = json_decode('{"ip":"' . $ipAddress . '","city":"Mountain View","region":"California","country":"US","loc":"37.3860,-122.0838"}', true);
        $cache->put($ipAddress, $data, 2000);

        $this->app->setLocale('en');

        $info = GeoLocation::lookup($ipAddress);

        $this->assertInstanceOf(GeoLocationDetails::class, $info);
        $this->assertEquals($ipAddress, $info->getIp());
        $this->assertEquals('Mountain View', $info->getCity());
        $this->assertEquals('California', $info->getRegion());
        $this->assertEquals('United States', $info->getCountry());
        $this->assertEquals('US', $info->getCountryCode());
        $this->assertIsFloat($info->getLatitude());
        $this->assertIsFloat($info->getLongitude());
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation
     */
    public function testCountryLocaleChange()
    {
        /**@var \Illuminate\Cache\ArrayStore $cache*/
        $cache = $this->app->get('cache')->getStore();
        $ipAddress = '8.8.8.8';
        $data = json_decode('{"ip":"' . $ipAddress . '","city":"Mountain View","region":"California","country":"US","loc":"37.3860,-122.0838"}', true);
        $cache->put($ipAddress, $data, 2000);

        $this->app->setLocale('pt_BR');

        $info = GeoLocation::lookup($ipAddress);

        $this->assertInstanceOf(GeoLocationDetails::class, $info);
        $this->assertEquals('Estados Unidos', $info->getCountry());
        $this->assertEquals('US', $info->getCountryCode());
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation
     */
    public function testGelLocationException()
    {
        $this->app->setLocale('pt_BR');

        $this->expectException(GeoLocationException::class);
        GeoLocation::lookup('1222398333');
    }

    /**
     * @covers \Adrianorosa\GeoLocation\GeoLocation::countries()
     */
    public function testGetTranslationsOfCountriesLocale()
    {
        $en = GeoLocation::countries('en');
        $this->assertArrayHasKey('US', $en);
        $this->assertEquals('United States', $en['US']);

        $pt_BR = GeoLocation::countries('pt_BR');
        $this->assertArrayHasKey('US', $pt_BR);
        $this->assertEquals('Estados Unidos', $pt_BR['US']);
    }
}

<?php

namespace Adrianorosa\GeoLocation\Tests\Providers;

use GuzzleHttp\Client;
use Adrianorosa\GeoLocation\Tests\TestCase;
use Adrianorosa\GeoLocation\Providers\IpInfo;

class IpInfoTest extends TestCase
{
    /**
     * @covers \Adrianorosa\GeoLocation\Providers\IpInfo::lookup
     */
    public function testCacheData()
    {
        /**@var \Illuminate\Cache\ArrayStore $cache*/
        $cache = $this->app->get('cache')->getStore();

        $ipAddress = '127.0.0.1';
        $data = json_decode('{"ip": "127.0.0.1","city": "X","region": "X", "country": "XX","loc": "-23.5475,-46.6361"}', true);

        $cache->put($ipAddress, $data, 2000);
        $provider = new IpInfo(new Client(), $this->app->get('cache')->getStore());

        $detail = $provider->lookup($ipAddress);

        $this->assertEquals('127.0.0.1', $detail->getIp());
        $this->assertEquals('X', $detail->getCity());
        $this->assertEquals('X', $detail->getRegion());
        $this->assertEquals('XX', $detail->getCountry());
    }

}

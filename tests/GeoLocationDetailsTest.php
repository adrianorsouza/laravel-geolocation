<?php

namespace Adrianorosa\GeoLocation\Tests;

use Adrianorosa\GeoLocation\GeoLocationDetails;

class GeoLocationDetailsTest extends TestCase
{
    public function testParseData()
    {
        $data = json_decode(
            '{"ip": "44.110.29.111","city": "Sydney","region": "Sydney", "country": "AU","loc": "-23.5475,-46.6361","postal": "01000-000"}',
            true
        );

        $details = new GeoLocationDetails($data);

        $this->assertEquals('44.110.29.111', $details->getIp());
        $this->assertEquals('Sydney', $details->getCity());
        $this->assertEquals('Sydney', $details->getRegion());
        $this->assertEquals('Australia', $details->getCountry());
        $this->assertEquals(-23.5475, $details->getLatitude());
        $this->assertEquals(-46.6361, $details->getLongitude());
    }

    public function testParseBogon()
    {
        $data = json_decode('{"ip": "192.168.10.6","bogon": true}', true);

        $details = new GeoLocationDetails($data);

        $this->assertEquals('192.168.10.6', $details->getIp());
        $this->assertEquals(null, $details->getCity());
        $this->assertEquals(null, $details->getRegion());
        $this->assertEquals(null, $details->getCountry());
        $this->assertEquals(null, $details->getLatitude());
        $this->assertEquals(null, $details->getLongitude());
    }

    public function testParseNull()
    {
        $data = json_decode('{"invalid JSON ERROR}', true);

        $details = new GeoLocationDetails($data);

        $this->assertEquals(null, $details->getIp());
        $this->assertEquals(null, $details->getCity());
        $this->assertEquals(null, $details->getRegion());
        $this->assertEquals(null, $details->getCountry());
        $this->assertEquals(null, $details->getLatitude());
        $this->assertEquals(null, $details->getLongitude());
    }
}

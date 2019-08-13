<?php

namespace Adrianorosa\GeoLocation;

use Illuminate\Support\Facades\Facade;

/**
 * Class GeoLocation.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 10:02
 *
 * @package Adrianorosa
 *
 * @method static|GeoLocationDetails lookup($ipAddress)
 *
 * @see \Adrianorosa\GeoLocation\IpInfo
 */
class GeoLocation extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geolocation';
    }
}

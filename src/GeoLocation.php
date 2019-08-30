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
 * @method static|GeoLocationDetails lookup($ipAddress, $responseFilter = 'geo')
 * @method static|\Adrianorosa\GeoLocation\Contracts\LookupInterface driver($name)
 *
 * @see \Adrianorosa\GeoLocation\Providers\IpInfo
 */
class GeoLocation extends Facade
{
    /**
     * Convenient method to get the translation list of countries codes.
     *
     * @param  null $locale
     *
     * @return mixed
     */
    public static function countries($locale = null)
    {
        return static::$app['translator']->get('geolocation::countries', [], $locale);
    }

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geolocation';
    }
}

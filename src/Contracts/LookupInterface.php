<?php

namespace Adrianorosa\GeoLocation\Contracts;

/**
 * LookupInterface.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 17:50
 *
 * @package Adrianorosa\GeoLocation
 */
interface LookupInterface
{
    /**
     * @param  string $ipAddress
     *
     * @param  string $responseFilter
     *
     * @return \Adrianorosa\GeoLocation\GeoLocationDetails
     */
    public function lookup($ipAddress, $responseFilter = 'geo');
}

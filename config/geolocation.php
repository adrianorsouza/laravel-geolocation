<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default IP Lookup Driver
    |--------------------------------------------------------------------------
    |
    | As a default driver, we use the IpInfo Service. in the future we may add
    | support for GeoIp by MaxMind, but for now we will just support IpInfo.
    |
    */

    'drivers' => [
        'default' => env('GEOLOCATION_DRIVER',  'ipinfo'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurations for each Provider
    |--------------------------------------------------------------------------
    |
    | If needed, configuration can be set for each individual provider. Right
    | there is only one driver available, but additional providers will be
    | added in future releases.
    |
    */

    'providers' => [

        'ipinfo' => [
            'driver' => 'ipinfo',
            'access_token' => env('GEOLOCATION_IPINFO_ACCESS_TOKEN', null),
            // These options are passed to GuzzleClient config constructor
            'client_options' => [
                //
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache configuration
    |--------------------------------------------------------------------------
    |
    | When using API requests like IpInfo and MaxMind WebService the results
    | can be cached to avoid repeat lookups for the same IP address. This
    | can greatly speed up the processing of IP addresses and has been
    | given a suitably high default value of 24 hours.
    */

    'cache' => [
        'ttl' => env('GEOLOCATION_CACHE_TTL', 86400),
    ],

];

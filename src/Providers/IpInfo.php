<?php

namespace Adrianorosa\GeoLocation\Providers;

use Illuminate\Contracts\Cache\Store;
use GuzzleHttp\Exception\GuzzleException;
use Adrianorosa\GeoLocation\GeoLocationDetails;
use Adrianorosa\GeoLocation\GeoLocationException;
use Adrianorosa\GeoLocation\Contracts\LookupInterface;

/**
 * Class IpInfo.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 13:55
 *
 */
class IpInfo implements LookupInterface
{
    /**
     * @const Define the baseurl.
     */
    const BASEURL = 'https://ipinfo.io';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $cache;

    /**
     * IpInfo constructor.
     *
     * @param $client
     * @param $cache
     */
    public function __construct($client, Store $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * Filter the API response down to specific fields or objects
     * by adding the field or object name to the URL.
     *
     * @param  string|null $ipAddress  An Ip or 'me' For yourself IP
     *
     * @param  string $responseFilter Options are: (city / org / geo)
     *
     * @return \Adrianorosa\GeoLocation\GeoLocationDetails
     * @throws \Adrianorosa\GeoLocation\GeoLocationException
     */
    public function lookup($ipAddress = null, $responseFilter = 'geo'): GeoLocationDetails
    {
        // For instance only `geo` filter are accepted, other type of filters
        // need a different parse approach for GeoLocationDetails which may
        // lead to a creation of a new properties and methods to accept strings
        $filter = $responseFilter !== 'geo' ? 'geo' : $responseFilter;

        if (is_null($data = $this->cache->get($ipAddress))) {
            $endpoint = static::BASEURL;
            $accessToken = config('geolocation.providers.ipinfo.access_token');

            if ($ipAddress) {
                $endpoint .= "/{$ipAddress}/{$filter}";
            }

            try {
                $response = $this->client->get(
                    $endpoint,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Accept' => 'application/json'
                        ],
                    ]
                );

                $data = json_decode(
                    $result = $response->getBody()->getContents(),
                    true
                );

                // Sometimes the response can be a string which will result to a JSON_ERROR
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data = $result;
                }

                $this->cache->put(
                    $ipAddress,
                    $data,
                    config('geolocation.cache.ttl')
                );
            } catch (GuzzleException | \Exception $e) {
                throw new GeoLocationException($e->getMessage());
            }
        }

        return new GeoLocationDetails($data);
    }
}

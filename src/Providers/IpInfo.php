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
 * @package Adrianorosa\GeoLocation
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
     * @param  string $responseFilter
     *
     * @return \Adrianorosa\GeoLocation\GeoLocationDetails
     * @throws \Adrianorosa\GeoLocation\GeoLocationException
     */
    public function lookup($ipAddress, $responseFilter = 'geo')
    {
        if (is_null($data = $this->cache->get($ipAddress))) {

            $endpoint = static::BASEURL;
            $accessToken = config('geolocation.providers.ipinfo.access_token');

            try {

                $response = $this->client->get(
                    "{$endpoint}/{$ipAddress}/{$responseFilter}", [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Accept' => 'application/json'
                        ],
                ]);

                $data = json_decode($response->getBody()->getContents(), 1);

                $this->cache->put(
                    $ipAddress, $data, config('geolocation.providers.ipinfo.cache.ttl')
                );

            } catch (GuzzleException $e) {

                throw new GeoLocationException($e->getMessage(), $e->getCode());

            } catch (\Exception $e) {

                throw new GeoLocationException($e->getMessage());
            }
        }

        return new GeoLocationDetails($data);
    }
}

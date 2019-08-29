<?php

namespace Adrianorosa\GeoLocation;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Class GeoLocationDetails.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 13:24
 *
 * @package Adrianorosa\GeoLocation
 */
class GeoLocationDetails implements \JsonSerializable, Arrayable
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * GeoLocationDetails constructor.
     *
     * @param  array $data
     */
    public function __construct($data = [])
    {
        $this->parse($data);
    }

    /**
     * Get the IP Address.
     *
     * @return string|null
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the City name.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get teh region name.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get the country name.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get the country ISO Code.
     *
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Get the Latitude value.
     *
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the Longitude value.
     *
     * @return float|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Parses the raw response.
     *
     * @param  mixed $data
     */
    protected function parse($data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        foreach ((array) $data as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'country') {
                    $this->countryCode = $value;
                    $this->{$key} = $this->formatCountry($value);
                } else {
                    $this->{$key} = $value;
                }
            }

            if ($key === 'loc') {
                $this->formatCoordinates($value);
            }
        }
    }

    /**
     * Parses the coordinates values into latitude and longitude.
     *
     * @param  string $value
     */
    protected function formatCoordinates($value): void
    {
        [$latitude, $longitude] = explode(',', $value);

        $this->latitude = (float) $latitude;
        $this->longitude = (float) $longitude;
    }

    /**
     * Format the country name.
     *
     * @param  string $countryCode
     *
     * @return mixed
     */
    protected function formatCountry($countryCode)
    {
        $countries = trans('geolocation::countries');

        return array_key_exists($countryCode, $countries) ? $countries[$countryCode] : $countryCode;
    }

    /**
     * Get list of location items as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'city' => $this->city,
            'region' => $this->region,
            'country' => $this->country,
            'countryCode' => $this->countryCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}

<?php

namespace Adrianorosa\GeoLocation;

use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * Class GeoLocationManager.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 11:50
 *
 * @package Adrianorosa\GeoLocation
 */
class GeoLocationManager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cacheProvider;

    /**
     * GeoLocation constructor.
     *
     * @param  array $config
     * @param  \Illuminate\Cache\CacheManager $cacheProvider
     */
    public function __construct($config, \Illuminate\Cache\CacheManager $cacheProvider)
    {
        $this->config = $config;
        $this->cacheProvider = $cacheProvider;

        $this->setDefaultDriver();
    }

    /**
     * Get a GeoLocation driver instance.
     *
     * @param $name
     *
     * @return mixed
     */
    public function driver($name)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->providers[$name] = $this->provider($name);
    }

    /**
     * @param  null $name
     */
    protected function setDefaultDriver($name = null)
    {
        $provider = $name ?? $this->getDefaultDriver();

        if ($provider) {
            $this->providers[$name] = $this->resolve($provider);
        }
    }

    /**
     * @return mixed
     */
    protected function getDefaultDriver()
    {
        return $this->config['drivers']['default'];
    }

    /**
     * @param  array $config
     *
     * @return \Adrianorosa\GeoLocation\Contracts\LookupInterface
     */
    protected function createIpinfoDriver($config)
    {
        $options = $config['client_options'] ?? [];

        return new Providers\IpInfo(new Client($options), $this->cacheProvider->getStore());
    }

    /**
     * @param  null $name
     *
     * @return mixed
     */
    protected function provider($name = null)
    {
        return $this->providers[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given store.
     *
     * @param  string  $name
     * @return \Adrianorosa\GeoLocation\Contracts\LookupInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("GeoLocation Driver [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("GeoLocation Driver [{$config['driver']}] is not supported.");
        }
    }

    /**
     * @param $name
     *
     * @return string|null
     */
    protected function getConfig($name)
    {
        return $this->config['providers'][$name] ?? null;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->provider()->$method(...$parameters);
    }
}

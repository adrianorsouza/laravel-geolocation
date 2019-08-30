<?php

namespace Adrianorosa\GeoLocation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class GeoLocationServiceProvider.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 10:04
 *
 * @package Adrianorosa
 */
class GeoLocationServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/geolocation.php', 'geolocation');

        $this->app->singleton('geolocation', function ($app) {
            /**@var \Illuminate\Foundation\Application $app*/
            return new GeoLocationManager(
                config('geolocation'),
                $app->get('cache')
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/geolocation.php' => config_path('geolocation.php'),
        ], 'geolocation-config');

        $this->publishes([
            __DIR__ . '/../translations' => resource_path('lang/vendor/geolocation')
        ], 'geolocation-translations');

        $this->loadTranslationsFrom(__DIR__ . '/../translations', 'geolocation');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\GeoLocationCommand::class,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return ['geolocation'];
    }
}

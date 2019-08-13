Laravel GeoLocation Package
===========================

Laravel package to get the details about Region, City and Country for a given IP Address.

This package is a wrapper for IpInfo Provider, however we have plans to implement a driver for 
MaxMind GeoIP2 databases.

### Install

    composer require adrianorosa/laravel-geolocation

> This package supports the latest version of Laravel, for now 5.8+

As of Laravel 5.5 there is no need to add the ServiceProvider within the `AppServiceProvider` array. 
Laravel loads this provider using Package Discovery.

### Usage

There is no need to additional setup to start using, once you install it via composer you can call the Facade:

```php
$details = GeoLocation::lookup('8.8.8.8');

echo $details->getCity()
// California

echo $details->getCountry()
// United States
``` 

## Override Configuration and translations

This package comes with a little configuration for IpInfo and translations for Country Names.

You can stick with default values, which works fine, or publish using the following commands:

Publish all config and translations 

    php artisan vendor:publish

Publish config

    php artisan vendor:publish --tag=geolocation-config
    
Publish translations

    php artisan vendor:publish --tag=geolocation-translations
    
    
## Author

**Adriano Rosa** (https://adrianorosa.com)  

## Licence

Copyright © 2019, Adriano Rosa  <info@adrianorosa.com>
All rights reserved.

For the full copyright and license information, please view the LICENSE 
file that was distributed within the source root of this project.

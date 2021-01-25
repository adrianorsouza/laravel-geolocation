<?php

namespace Adrianorosa\GeoLocation\Console;

use Illuminate\Console\Command;
use Adrianorosa\GeoLocation\GeoLocation;

/**
 * Class GeoLocationCommand.
 *
 * @author Adriano Rosa <https://adrianorosa.com>
 * @date 2019-08-13 20:13
 *
 * @package Adrianorosa\GeoLocation\Console
 */
class GeoLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geolocation:lookup {--ip= : The Ip Address to get the details for.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The Console GeoLocation Console Command allows one to call this in the background';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ipAddress = $this->option('ip');

        $data = GeoLocation::lookup($ipAddress);

        $data = array_merge(['ip' => $data->getIp()], $data->toArray());

        $this->table(array_keys($data), [array_values($data)]);
    }
}

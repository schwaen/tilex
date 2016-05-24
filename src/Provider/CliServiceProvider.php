<?php

namespace Tilex\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tilex\Console\Application;

class CliServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['cli'] = function ($app) {
            $cli = new Application($app['app.name'], $app['app.version']);
            $cli->setContainer($app);
            return $cli;
        };
    }
}

<?php
namespace Tilex\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tilex\Console\Application;

/**
 * Register the CliServiceProvider
 */
class CliServiceProvider implements ServiceProviderInterface
{
    /**
     * (non-PHPdoc)
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['cli'] = function ($app) {
            $cli = new Application($app['app.name'], $app['app.version']);
            $cli->setContainer($app);
            return $cli;
        };
    }
}

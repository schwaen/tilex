<?php
namespace Tilex\Provider;

use Tilex\Cors;
use Silex\Application;
use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Register the CorsServiceProvider
 */
class CorsServiceProvider implements ServiceProviderInterface
{
    /**
     * (non-PHPdoc)
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['cors'] = function ($app) {
            $options = [];
            foreach (['allowedOrigins', 'allowedMethods', 'allowedHeaders', 'magAge', 'allowCredentials'] as $option) {
                if (isset($app['cors.'.$option])) {
                    $options[$option] = $app['cors.'.$option];
                }
            }
            return new Cors($options);
        };
        $app->before(function (Request $request) use ($app) {
            return $app['cors']->handlePreflightRequest($request);
        });
        $app->after(function (Request $request, Response $response)  use ($app) {
            $app['cors']->handleRequest($request, $response);
        });
    }
}

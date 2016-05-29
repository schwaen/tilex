<?php

namespace Tilex\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tilex\Annotation\AnnotationHandler;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

/**
 * Register the AnnotationServiceProvider
 */
class AnnotationServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * (non-PHPdoc)
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        if (!isset($app['annotation.dirs'])) {
            $app['annotation.dirs'] = [];
        }
        $app['annotation'] = function ($app) {
            return new AnnotationHandler($app, $app['annotation.dirs']);
        };
        AnnotationRegistry::registerLoader(function ($class) { return class_exists($class); });
    }

    /**
     * (non-PHPdoc)
     * @see \Silex\Api\BootableProviderInterface::boot()
     */
    public function boot(Application $app)
    {
        $app['annotation']->handle();
    }
}

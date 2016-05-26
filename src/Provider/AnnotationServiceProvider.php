<?php

namespace Tilex\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tilex\Annotation\AnnotationHandler;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

class AnnotationServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
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
    
    public function boot(Application $app)
    {
        $app['annotation']->handle();
    }
}

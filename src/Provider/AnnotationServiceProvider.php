<?php

namespace Tilex\Provider;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tilex\Annotation\AnnotationHandler;
use Doctrine\Common\Annotations\AnnotationRegistry;

class AnnotationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['annotation'] = function ($app) {
            return new AnnotationHandler($app);
        };

        AnnotationRegistry::registerLoader(function ($class) { return class_exists($class); });
    }
}

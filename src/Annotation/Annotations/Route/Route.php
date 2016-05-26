<?php
namespace Tilex\Annotation\Annotations\Route;

use Tilex\Annotation\AnnotationMethodProcessInterface;
use Tilex\Annotation\Annotations\Controller;
use Silex\Application;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Route implements AnnotationMethodProcessInterface
{
    /** @var string */
    public $method;

    /** @var string */
    public $uri;
    
    public function process(Application $app, \ReflectionMethod $method, array $class_annotations = [])
    {
        $controller = $app->match($this->uri, $method->class.'::'.$method->name)->method($this->method);
    }
}

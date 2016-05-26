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
    
    /** @var bool */
    public $requireHttp;
    
    /** @var bool */
    public $requireHttps;
    
    public function ___construct(array $values)
    {
        echo'<pre>';
        print_r($values);
    }
    
    public function process(Application $app, \ReflectionMethod $method, array $class_annotations = [])
    {
        /** @var \Silex\Controller */
        $controller = $app->match($this->uri, $method->class.'::'.$method->name)->method($this->method);
        if ($this->requireHttp) {
            $controller->requireHttp();
        }
        if ($this->requireHttps) {
            $controller->requireHttps();
        }
    }
}

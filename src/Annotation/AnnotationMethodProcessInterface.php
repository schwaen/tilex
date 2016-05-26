<?php
namespace Tilex\Annotation;

use Silex\Application;

interface AnnotationMethodProcessInterface
{
    public function process(Application $app, \ReflectionMethod $method, array $class_annotations = []);
}

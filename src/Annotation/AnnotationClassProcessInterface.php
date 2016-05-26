<?php
namespace Tilex\Annotation;

use Silex\Application;

interface AnnotationClassProcessInterface
{
    public function process(Application $app, \ReflectionClass $class);
}

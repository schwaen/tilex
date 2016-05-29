<?php
namespace Tilex\Annotation;

use Silex\Application;

/**
 * Interface for processable annotation methods
 */
interface AnnotationMethodProcessInterface
{
    /**
     * Process an annotation
     * @param Application $app
     * @param \ReflectionMethod $method
     * @param array $class_annotations
     */
    public function process(Application $app, \ReflectionMethod $method, array $class_annotations = []);
}

<?php
namespace Tilex\Annotation;

use Silex\Application;

/**
 * Interface for processable annotation classes
 */
interface AnnotationClassProcessInterface
{
    /**
     * Process an annotation
     * @param Application $app
     * @param \ReflectionClass $class
     */
    public function process(Application $app, \ReflectionClass $class);
}

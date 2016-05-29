<?php
namespace Tilex\Annotation\Annotations\Routing;

use Tilex\Annotation\AnnotationClassProcessInterface;
use Silex\Application;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Controller implements AnnotationClassProcessInterface
{
    /** @var string */
    public $prefix;

    /** @var \Silex\ControllerCollection */
    public $controller_collection = null;

    /**
     * (non-PHPdoc)
     * @see \Tilex\Annotation\AnnotationClassProcessInterface::process()
     */
    public function process(Application $app, \ReflectionClass $class)
    {
        $this->controller_collection = $app['controllers_factory'];
        $app->mount($this->prefix, $this->controller_collection);
    }
}

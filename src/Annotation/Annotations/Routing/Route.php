<?php
namespace Tilex\Annotation\Annotations\Routing;

use Tilex\Annotation\AnnotationMethodProcessInterface;
use Tilex\Annotation\Annotations\Routing\Assert;
use Tilex\Annotation\Annotations\Routing\Convert;
use Tilex\Annotation\Annotations\Routing\Value;
use Tilex\Annotation\Annotations\Routing\Controller;
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

    /** @var string */
    public $bind;

    /** @var bool */
    public $requireHttp;

    /** @var bool */
    public $requireHttps;

    /** @var Tilex\Annotation\Annotations\Routing\Assert[] */
    public $asserts=[];

    /** @var Tilex\Annotation\Annotations\Routing\Convert[] */
    public $converter=[];

    /** @var Tilex\Annotation\Annotations\Routing\Value[] */
    public $values=[];

    public function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if($key !== 'value' && !is_array($value)) {
                $this->{$key} = $value;
            } else {
                foreach ($value as $anno) {
                    if ($anno instanceof Assert) {
                        $this->asserts[] = $anno;
                    } elseif ($anno instanceof Convert) {
                        $this->converter[] = $anno;
                    } elseif ($anno instanceof Value) {
                        $this->values[] = $anno;
                    }
                }
            }
        }
    }

    public function process(Application $app, \ReflectionMethod $method, array $class_annotations = [])
    {
        $context = $app;
        foreach ($class_annotations as $c_annotation) {
            if($c_annotation instanceof Controller){
                $context = $c_annotation->controller_collection;
                break;
            }
        }
        /** @var \Silex\Controller */
        $controller = $context->match($this->uri, $method->class.'::'.$method->name)->method($this->method);
        if ($this->requireHttp) {
            $controller->requireHttp();
        }
        if ($this->requireHttps) {
            $controller->requireHttps();
        }
        if (!empty($this->bind)) {
            $controller->bind($this->bind);
        }
        foreach ($this->asserts as $assert) {
            $controller->assert($assert->variable, $assert->regex);
        }
        foreach ($this->converter as $convert) {
            $controller->convert($convert->variable, $convert->callback);
        }
        foreach ($this->values as $value) {
            $controller->value($value->variable, $value->value);
        }
    }
}

<?php
namespace Tilex\Annotation\Annotations\Routing;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Convert
{
    /** @var string */
    public $variable;

    /** @var string */
    public $callback;
}

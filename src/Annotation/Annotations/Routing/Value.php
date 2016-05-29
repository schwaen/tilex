<?php
namespace Tilex\Annotation\Annotations\Routing;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Value
{
    /** @var string */
    public $variable;

    /** @var string */
    public $value;
}

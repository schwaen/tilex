<?php
namespace Tilex\Annotation\Annotations\Routing;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Assert
{
    /** @var string */
    public $variable;

    /** @var string */
    public $regex;
}

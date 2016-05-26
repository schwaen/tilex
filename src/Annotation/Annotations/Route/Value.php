<?php
namespace Tilex\Annotation\Annotations\Route;

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

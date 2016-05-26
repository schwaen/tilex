<?php
namespace Tilex\Annotation\Annotations\Route;

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

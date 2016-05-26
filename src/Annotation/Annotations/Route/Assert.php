<?php
namespace Tilex\Annotation\Annotations\Route;

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

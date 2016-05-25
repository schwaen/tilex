<?php
namespace Tilex\Annotation\Annotations\Route;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Controller
{
    /** @var string */
    public $prefix;
}

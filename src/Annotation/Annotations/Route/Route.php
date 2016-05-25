<?php
namespace Tilex\Annotation\Annotations\Route;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Route
{
    /** @var string */
    public $method;

    /** @var string */
    public $uri;
}

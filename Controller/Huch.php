<?php
namespace Controller;

/**
 */
class Huch
{
    /**
     * @Tilex\Annotation\Annotations\Routing\Route(
     *     method="GET",
     *     uri="/hello/{name}"
     * )
     */
    public function moooooo($name)
    {
        return __METHOD__.' '.$name;
    }

    /**
     * @Tilex\Annotation\Annotations\Routing\Route(
     *     method="GET",
     *     uri="/rand"
     * )
     * @Tilex\Annotation\Annotations\Routing\Route(
     *     method="GET",
     *     uri="/rand2"
     * )
     */
    public function rand()
    {
        return rand(0,100);
    }
}
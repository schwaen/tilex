<?php
namespace Controller;

/**
 */
class Wurst
{
    /**
     * @Tilex\Annotation\Annotations\Route\Route(
     *     method="GET",
     *     uri="/rand3"
     * )
     */
    public function rand() 
    {
        return rand(0,100).'_____';
    }
}
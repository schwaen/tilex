<?php
namespace Controller;

/**
 */
class Wurst
{
    /**
     * @Tilex\Annotation\Annotations\Route\Route(
     *     method="GET",
     *     uri="/rand3",
     *     requireHttps=false
     * )
     */
    public function rand() 
    {
        return rand(0,100).'_____';
    }
}
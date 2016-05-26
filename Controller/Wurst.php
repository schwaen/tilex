<?php
namespace Controller;

use Tilex\Annotation\Annotations\Route as AR;

/**
 */
class Wurst
{
    /**
     * @AR\Route(
     *     method="GET",
     *     uri="/rand3/{min}/{max}",
     *     requireHttp=false,
     *     @AR\Assert(variable="min", regex="\d+"),
     *     @AR\Assert(variable="max", regex="\d+"),
     *     @AR\Convert(variable="min", callback="Controller\Wurst::convertToInt"),
     *     @AR\Convert(variable="max", callback="Controller\Wurst::convertToInt"),
     *     @AR\Value(variable="max", value="150"),
     * )
     */
    public function rand($min, $max) 
    {
        var_dump($min, $max);
        return rand($min,$max).'_____';
    }
    
    public static function convertToInt($var)
    {
        return (int)$var;
    }
}
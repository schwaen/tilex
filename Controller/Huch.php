<?php
namespace Controller;

/**
 * @Tilex\Annotation\Annotations\Controller(prefix="/test")
 */
class Huch
{
    /**
     * @Tilex\Annotation\Annotations\Route\Route(
     *     method="GET",
     *     uri="/hello/world"
     * )
     */
    public function foo()
    {
        return 'MUUH';
    }
}
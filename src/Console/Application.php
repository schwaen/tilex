<?php
namespace Tilex\Console;

use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    protected $tilex = null;

    public function setTilex(\Tilex\Application $app)
    {
        $this->tilex = $app;
    }

    public function getTilex()
    {
        return $this->tilex;
    }
}

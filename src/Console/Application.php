<?php
namespace Tilex\Console;

use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    /**
     * @var \Tilex\Application
     */
    protected $tilex = null;

    /**
     * setTilex
     * @param \Tilex\Application $app
     */
    public function setTilex(\Tilex\Application $app)
    {
        $this->tilex = $app;
    }

    /**
     * @return \Tilex\Application
     */
    public function getTilex()
    {
        return $this->tilex;
    }
}

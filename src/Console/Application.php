<?php
namespace Tilex\Console;

use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    /**
     * @var \Pimple\Container
     */
    protected $container = null;

    /**
     * setContainer
     * @param \Pimple\Container $container
     */
    public function setContainer(\Pimple\Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Pimple\Container
     */
    public function getContainer()
    {
        return $this->container;
    }
}

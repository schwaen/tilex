<?php
namespace Tilex;

use Silex\Application as BaseApplication;
use Tilex\Provider\CorsServiceProvider;
use Tilex\Provider\CliServiceProvider;
use Tilex\Console\Command\HttpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Command\Command;


class Application extends BaseApplication
{
    /** var string */
    const VERSION = '0.1.0-dev';

    /**
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        $services = [];
        if (isset($values['servies']) && is_array($values['servies'])) {
            $services = $values['servies'];
            unset($values['servies']);
        }
        $values['route_class'] = '\\Tilex\\Route';
        
        parent::__construct($values);
        $app = $this;
        if (!isset($this['app.name'])) {
            $this['app.name'] = 'Tilex';
        }
        if (!isset($this['app.version'])) {
            $this['app.version'] = self::VERSION;
        }

         $this->extend('route_factory', function($route_factory) use ($app) {
             if ($route_factory instanceof Route) {
                  $route_factory->setContainer($app);
             }
             return $route_factory;
         });

        $this->register(new CorsServiceProvider());
        $this->register(new CliServiceProvider());
        $this->cli(new HttpCommand());
        foreach ($services as $service => $services_values) {
            $this->register(new $service(), $services_values);
        }
    }

    /**
     * Register a cli command
     * @param Command $command
     */
    public function cli(Command $command)
    {
        $this['cli']->add($command);
    }

    /**
     * Returns true if the app is executed by the cli
     * @return boolean
     */
    public function isCli()
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * (non-PHPdoc)
     * @see \Silex\Application::run()
     */
    public function run(Request $request = null)
    {
        if ($this->isCli()) {
            /* @var $this['cli'] \Tilex\Console\Application */
            $this['cli']->run();
        } else {
            parent::run($request);
        }
    }
}

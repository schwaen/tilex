<?php
namespace Tilex;

use Tilex\Provider\AnnotationServiceProvider;
use Tilex\Provider\CliServiceProvider;
use Tilex\Console\Command\HttpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Command\Command;


class Application extends \Silex\Application
{
    public function __construct(array $values = array())
    {
        parent::__construct($values);

        $this['app.name'] = 'Tilex';
        $this['app.version'] = '0.1.0-dev';

        $this->register(new CliServiceProvider());
        $this->cli(new HttpCommand());
        $this->register(new AnnotationServiceProvider());
    }

    public function cli(Command $command)
    {
        $this['cli']->add($command);
    }

    public function isCli()
    {
        return php_sapi_name() === 'cli';
    }

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


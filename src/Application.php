<?php
namespace Tilex;

use Tilex\Provider\CliServiceProvider;
use Tilex\Console\Command\HttpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Command\Command;

class Application extends \Silex\Application
{
    public function __construct(array $values = array())
    {
        parent::__construct();

        $this['app.name'] = 'Tilex';
        $this['app.version'] = '1.0.0';

        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }

        $this->register(new CliServiceProvider());
    }

    public function cli(Command $command)
    {
        $this['cli']->add($command);
    }

    public function run(Request $request = null)
    {
        if (php_sapi_name() === 'cli') {
          /* @var $this['cli'] \Tilex\Console\Application */
          $this['cli']->run();
        } else {
          parent::run($request);
        }
    }
}

<?php
namespace Tilex\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('call:route')
            ->setDescription('Calls an internal http route')
            ->addArgument('uri',InputArgument::REQUIRED)
            ->addArgument('method', InputArgument::OPTIONAL, '', 'GET')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $input->getArgument('uri');
        $method = $input->getArgument('method');
        $request = Request::create($uri, $method);

        $app = $this->getApplication()->getTilex();
        /* @var $app \Tilex\Application */
        $res = $app->handle($request, HttpKernelInterface::SUB_REQUEST);

        if ($res->isOk()) {
            $output->writeln($res->getContent());
        } else {
            $formatter = $this->getHelper('formatter');
            $errorMessages = array('Error!', 'Something went wrong');
            $output->writeln($formatter->formatBlock($errorMessages, 'error'));
        }
    }
}

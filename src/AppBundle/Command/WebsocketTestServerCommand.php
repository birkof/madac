<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WebsocketTestServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('websocket:test-server')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $socketServer = new \WebSocket\Server(array('timeout' => 200));
        $output->writeln($socketServer->getPort(), "\n");

        try {
            while($connection = $socketServer->accept()) {
                while(1) {
                    $message = $socketServer->receive();

                    if ($message == "GET_MEASUREMENTS") {
                        $socketServer->send(json_encode(array(
                            'width'     =>  rand(1, 5),
                            'height'    =>  rand(1, 5),
                            'length'    =>  rand(1, 5)
                        )));
                    }
                }
            }
        } catch (\WebSocket\ConnectionException $ex) {
            $output->writeln("\n", microtime(true), " Client died: $ex\n");
        }
    }

}

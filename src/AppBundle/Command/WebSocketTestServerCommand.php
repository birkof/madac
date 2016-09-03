<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebSocket\Server;

class WebSocketTestServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('websocket:test-server')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $socketServer = new Server(array('timeout' => 200, 'port' => 8080));
        $output->writeln("Listening on port {$socketServer->getPort()}\n");

        while ($connection = $socketServer->accept()) {
            try {
                while (1) {
                    $message = $socketServer->receive();
                    $output->writeln("Received message $message");

                    if ($message == "GET_MEASUREMENTS") {
                        $messageToSend = json_encode(array(
                            'width'     =>  rand(1, 5),
                            'height'    =>  rand(1, 5),
                            'length'    =>  rand(1, 5)
                        ));
                        $socketServer->send($messageToSend);
                        $output->writeln("Sent message $messageToSend\n");
                    }
                }
            } catch (\WebSocket\ConnectionException $ex) {
                $output->writeln("\n", microtime(true), " Client died: $ex\n");
            }
        }
    }

}

<?php

namespace ChainCommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MasterCommand extends ContainerAwareCommand
{

    protected $mainCommand;
    protected $chainedCommands = [];

    public function setMainCommand($commandId)
    {
        $this->mainCommand = $commandId;
        return $this;
    }

    public function setChainedCommands($chainedCommands)
    {
        $this->chainedCommands = $chainedCommands;
        return $this;
    }

    protected function configure() {
        $this->setName('ccc:master');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $container = $this->getContainer();

        $container->get("{$this->mainCommand}_original")
            ->execute($input, $output);

        foreach ($this->chainedCommands as $serviceName) {
            $container
                ->get($serviceName)
                ->execute($input, $output);
        }
    }
}

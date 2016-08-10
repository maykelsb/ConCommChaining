<?php

namespace ChainCommandBundle\Chain;

use Symfony\Component\Console\Command\Command;


class CommandChain
{
    private $commands = [];

    public function addCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    public function getCommandChain($chainedTo)
    {
        if (array_key_exists($chainedTo, $this->commands)) {
            return $this->commands[$chainedTo];
        }
    }
}

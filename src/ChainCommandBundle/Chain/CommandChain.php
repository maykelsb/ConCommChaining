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
}

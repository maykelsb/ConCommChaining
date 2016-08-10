<?php
/**
 * This code is part of a solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md Test description.
 */

namespace BarBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * A simple command to evaluate ChainingCommand implementation.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class HiCommand extends Command
{
    /**
     * Configures the hi command.
     *
     * Setting its name, description and help message.
     */
    protected function configure()
    {
        $this->setName('bar:hi')
            ->setDescription('Use to say hi.')
            ->setHelp('This command allows you to say hi from Bar.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hi from Bar!');
    }
}

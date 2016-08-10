<?php
/**
 * A simple command to evaluate ChainingCommand implementation.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

namespace FooBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
class HelloCommand extends Command
{

    /**
     * Configures the hello command.
     *
     * Setting its name, description and help message.
     */
    protected function configure()
    {
        $this->setName('foo:hello')
            ->setDescription('Use to say hello.')
            ->setHelp('This command allows you to say hello from Foo.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello from Foo!');
    }
}

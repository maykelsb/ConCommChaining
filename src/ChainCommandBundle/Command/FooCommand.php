<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is a simple command class which says "foo".
 *
 * It is used in CommandChain tests.
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class FooCommand extends Command
{
    /**
     * Set command configuration.
     */
    protected function configure() {
        $this->setName('ccc:foo')
            ->setDescription('Used to test CommandChainBundle')
            ->setHelp('This command allows you to sai "foo".');
    }

    /**
     * Say "foo".
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('foo');
    }
}

<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\Tests\Fixtures\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is a simple command class which says "bar".
 *
 * It is used in CommandChain tests.
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class BarCommand extends Command
{
    /**
     * Set command configuration.
     */
    protected function configure() {
        $this->setName('ccc:bar')
            ->setDescription('Used to test CommandChainBundle')
            ->setHelp('This command allows you to sai "bar".');
    }

    /**
     * Say "bar".
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('bar');
    }
}

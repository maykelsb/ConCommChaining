<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is a dummy command which is used to be called in place of a chained command.
 *
 * The switch between this class and the chained command occurs in
 * CommandChainPass::hideChainedCommand().
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class DummyCommand extends ContainerAwareCommand
{
    /**
     * A basic configuration.
     */
    protected function configure() {
        $this->setName('ccc:dummy');
    }

    /**
     * A basic execution.
     *
     * @param InputInterface $input Common input interface.
     * @param OutputInterface $output Common output interface.
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('não chama eu');
    }
}

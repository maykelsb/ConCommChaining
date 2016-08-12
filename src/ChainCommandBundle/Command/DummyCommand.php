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
     * @var string Stores the main command name.
     */
    protected $mainCommand;

    /**
     * Set the main command name.
     *
     * @param string $mainCommand Main command name.
     * @return \ChainCommandBundle\Command\DummyCommand
     */
    public function setMainCommand($mainCommand)
    {
        $this->mainCommand = $mainCommand;
        return $this;
    }

    /**
     * Retrieves main command name.
     *
     * @return string
     */
    public function getMainCommand()
    {
        return $this->mainCommand;
    }

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
        $message = "Error: {$this->getName()} command is a member of "
            . "{$this->getMainCommand()} command chain and cannot be executed "
            . "on its own.";

        $output->writeln($message);
    }
}

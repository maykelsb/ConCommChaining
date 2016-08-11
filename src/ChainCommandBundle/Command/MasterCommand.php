<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\Command;

use ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * This is a master command which is used to call a chain of commands.
 *
 * When a command is tagged as a main chain command, that ordinary command is
 * switched with this command, so, it can be call the complete command chain.
 *
 * The switch between this class and the main command occurs in
 * CommandChainPass::activeMainCommandChain().
 */
class MasterCommand extends ContainerAwareCommand
{
    /**
     * @var string The service id of the main command.
     */
    protected $mainCommand;

    /**
     * @var string[] The complete list of all commands chained to this maincommand.
     */
    protected $chainedCommands = [];

    /**
     * Defines the id of the command service.
     *
     * @param string $commandId Service id of main command.
     * @return \ChainCommandBundle\Command\MasterCommand
     */
    public function setMainCommand($commandId)
    {
        $this->mainCommand = $commandId;
        return $this;
    }

    /**
     * Defines the list of chained commands.
     *
     * @param string[] $chainedCommands List of chained commands.
     * @return \ChainCommandBundle\Command\MasterCommand
     */
    public function setChainedCommands(array $chainedCommands)
    {
        $this->chainedCommands = $chainedCommands;
        return $this;
    }

    /**
     * A basic configuration.
     */
    protected function configure() {
        $this->setName('ccc:master');
    }

    /**
     * Executes the main command and all its chained commands.
     *
     * First of all, the main command is found and executed. After that, all
     * commands in the chained list is called, one after another.
     *
     * @param InputInterface $input Common input interface.
     * @param OutputInterface $output Common output interface.
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $container = $this->getContainer();

        $container->get($this->mainCommand . CommandChainPass::MAINCOMM_POSFIX)
            ->execute($input, $output);

        foreach ($this->chainedCommands as $serviceName) {
            $container
                ->get($serviceName . CommandChainPass::CHAINEDCOMM_POSFIX)
                ->execute($input, $output);
        }
    }
}

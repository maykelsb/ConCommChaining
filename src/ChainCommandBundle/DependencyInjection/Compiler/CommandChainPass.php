<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

use ChainCommandBundle\Command\DummyCommand;
use ChainCommandBundle\Command\MasterCommand;

/**
 *
 *
 *
 * Commands are identified by its service names.
 * @uses ChainCommandBundle\Command\DummyCommand
 */
class CommandChainPass implements CompilerPassInterface
{
    /**
     * Used to identify the original main command.
     */
    const MAINCOMM_POSFIX = '_main';

    /**
     * Used to identify the original chained command.
     */
    const CHAINEDCOMM_POSFIX = '_chained';

    /**
     * @var array Stores all the identified command chains.
     */
    protected $commandChains = [];

    /**
     * @var string[] Stores already discovered command names.
     */
    protected $commandNames = [];

    /**
     * @var ContainerBuilder A reference for the Container builder.
     */
    protected $container;

    /**
     * Sets a reference for the container builder.
     *
     * @param ContainerBuilder $container
     * @return \ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass
     */
    protected function setContainer(ContainerBuilder $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Gets the container builder reference.
     *
     * @return ContainerBuilder
     */
    protected function getContainer()
    {
        return $this->container;
    }

    public function process(ContainerBuilder $container)
    {
        $this->setContainer($container);

        // -- Finding out which commands are chained and to whom.
        $this->loadChainedCommands();

        // -- Changing services so they can work as a chain of commands
        foreach ($this->commandChains as $service => $chainedCommands) {
            foreach ($chainedCommands as $chainedComm) {
                // -- Hidding chained commands
                $this->hideChainedCommand($chainedComm);
            }
            // -- Changing the main command so it can call all its chain
            $this->activeMainCommandChain($service, $chainedCommands);
        }
    }

    protected function activeMainCommandChain($serviceId, array $chainedCommands)
    {
        // -- Hidding the master command in another service, so it can only be
        // -- called from MainCommand class
        $servDefinition = $this->getContainer()->findDefinition($serviceId);
        $this->getContainer()->setDefinition(
            $serviceId . self::MAINCOMM_POSFIX,
            clone $servDefinition
        );

        // -- Storing the command name as it is class based, when the class
        // -- is changed, the command name will change too.
        $commandName = $this->retrieveCommandName($servDefinition);

        // -- Reconfiguring the MasterCommand so it can answer calls of the main
        // -- command and call its chained commands.
        $servDefinition->setClass(MasterCommand::class)
            // -- Changing name of MasterCommand
            ->addMethodCall('setName', [$commandName])
            // -- Setting which one is the main command on the chain
            ->addMethodCall('setMainCommand', [$serviceId])
            // -- Setting its command chain
            ->addMethodCall('setChainedCommands', [$chainedCommands]);
    }

    /**
     * Hide all chained commands and putting a dummy command on its places.
     *
     * Create a new service with a posfix to avoid the chainned commands to be
     * called directly. Every time they are called in the console, the
     * DummyCommand will be used in its place.
     *
     * @param string $serviceId The service id of the chained command.
     */
    protected function hideChainedCommand($serviceId)
    {
        // -- Finding the service definition
        $servDefinition = $this->getContainer()
            ->findDefinition($serviceId);

        // -- Hidding it in another service, so it can only be called from its main command
        $this->getContainer()->setDefinition(
            $serviceId . self::CHAINEDCOMM_POSFIX,
            clone $servDefinition
        );

        // -- Changing the class definition of the service to a dummy command class
        $servDefinition->setClass(DummyCommand::class)
            // -- Changing the name of the dummy command so it can be called in place
            ->addMethodCall('setName', [$this->retrieveCommandName($servDefinition)]);
    }

    /**
     * Finds out the services tagged with "chaincommand.chained" and builds the
     * CommandChainPass::$commandChains array.
     *
     * It uses the tag atribute 'chainto' to find out which is its main main command.
     *
     * @example
     * <code>
     * $this->commandChains = [
     *   'mainServiceId' => [
     *      'chainedServiceId1', 'chainedServiceId2'
     *   ]
     * ];
     * </code>
     */
    protected function loadChainedCommands()
    {
        $taggedServices = $this->getContainer()
            ->findTaggedServiceIds('chaincommand.chained');
        foreach ($taggedServices as $chainedCommand => $tags) {

            // -- processing tag properties
            foreach ($tags as $attribs) {
                $mainCommand = $attribs['chainto'];
                $this->addToChain($mainCommand, $chainedCommand);
            }
        }
    }

    /**
     * Adds a chained command to its main command chain.
     *
     * If needed it starts a new command chain to add the new chainned command,
     * it also avoid repetitions when doing insertions.
     *
     * @param string $mainCommand The main command service id.
     * @param string $chainedCommand The chained command service id.
     */
    protected function addToChain($mainCommand, $chainedCommand)
    {
        if (!key_exists($mainCommand, $this->commandChains)) {
            // -- Starting a command chain with its main command
            $this->commandChains[$mainCommand] = [$chainedCommand];
        }

        if (!in_array($chainedCommand, $this->commandChains[$mainCommand])) {
            // -- Adding commands to a exiting chain, avoding repetitions
            $this->commandChains[$mainCommand][] = $chainedCommand;
        }
    }

    /**
     * Retrieves a command name from its service definition.
     *
     * Get the command class from its definition object, instantiate it and
     * call Command::getClass() to retrieves the command name.
     *
     * @param Definition $definition Service definition of a command.
     * @return string Command name.
     */
    protected function retrieveCommandName(Definition $definition)
    {
        $commandClass = $definition->getClass();

        // -- It is not already discovered?
        if (!key_exists($commandClass, $this->commandNames)) {

            // -- So, find it out
            $command = new $commandClass();
            $this->commandNames[$commandClass] = $command->getName();
        }

        return $this->commandNames[$commandClass];
    }
}

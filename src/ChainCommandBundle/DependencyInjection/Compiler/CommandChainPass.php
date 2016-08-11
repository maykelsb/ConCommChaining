<?php

namespace ChainCommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 *
 *
 *
 * Commands are identified by its service names.
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
        if (!$container->has('chaincommand.command_chain')) {
            return;
        }

        $this->setContainer($container);
        $this->loadChainedCommands();

        foreach ($this->commandChains as $service => $chainedCommands) {
            foreach ($chainedCommands as $chainedComm) {
//                $chainedCommDef = $container->findDefinition($chainedComm);
//                $container->setDefinition("{$chainedComm}_chained", clone $chainedCommDef);
//
//                $commandClass = $chainedCommDef->getClass();
//                $command = new $commandClass();
//
//                $chainedCommDef->setClass('ChainCommandBundle\Command\ChainedCommand')
//                    ->addMethodCall('setName', [$command->getName()]);
            }

            $mainCommandDef = $container->findDefinition($service);
            $container->setDefinition("{$service}_master", clone $mainCommandDef);
            $mainCommandDef->addMethodCall('setName', [
                $this->retrieveCommandName($mainCommandDef)
            ])->setClass("ChainCommandBundle\Command\MasterCommand")
                ->addMethodCall('setMainCommand', [$service])
                ->addMethodCall('setChainedCommands', [$chainedCommands]);

        }
    }

    protected function cloneMainCommand(
        Definition $command,
        $serviceId,
        array $chainedCommands
    ) {

    }

    protected function cloneChainedCommand($serviceId)
    {
        $serviceDefinition = $container->findDefinition($serviceId);



//                $chainedCommDef =
//                $container->setDefinition("{$chainedComm}_chained", clone $chainedCommDef);
//
//                $commandClass = $chainedCommDef->getClass();
//                $command = new $commandClass();
//
//                $chainedCommDef->setClass('ChainCommandBundle\Command\ChainedCommand')
//                    ->addMethodCall('setName', [$command->getName()]);
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

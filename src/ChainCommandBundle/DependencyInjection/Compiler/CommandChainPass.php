<?php

namespace ChainCommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Definition;

class CommandChainPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('chaincommand.command_chain')) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds('chaincommand.chained');

        $servicesWithChain = [];
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {

                if (!array_key_exists($attributes['chainto'], $servicesWithChain)) {
                    $servicesWithChain[$attributes['chainto']] = [$id];
                }

                if (!in_array($id, $servicesWithChain[$attributes['chainto']])) {
                    $servicesWithChain[$attributes['chainto']][] = $id;
                }
            }
        }

        foreach ($servicesWithChain as $service => $chained) {
            $mainCommandDef = $container->findDefinition($service);

            $container->setDefinition("{$service}_original", clone $mainCommandDef);


            $definition = $container->findDefinition($service);
            $definition->setClass("ChainCommandBundle\Command\MasterCommand");
            $definition->addMethodCall('setName', ['foo:hello'])
                ->addMethodCall('setMainCommand', [$service])
                ->addMethodCall('setChainedCommands', [$chained]);

        }
    }
}

<?php

namespace ChainCommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Console\Command\Command;

class CommandChainPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('chaincommand.command_chain')) {
            return;
        }

        $definition = $container->findDefinition('chaincommand.command_chain');
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
//                var_dump($definition->addMethodCall('addCommand', [
//                    new Reference($id),
//                    $attributes['chainto']
//                ]));

            }
        }

        foreach ($servicesWithChain as $service => $chained) {
            $command = $container->get($service)
                ->setCode(function(InputInterface $input, OutputInterface $output){
                $output->writeln('novo');
            });

            var_dump($command);



//            Command $command = $container->get($service);
//            var_dump(
//                ((Command)$command)->getCode()
//            );

//            var_dump($command);
        }



//        var_dump($servicesWithChain);
//        die();
    }
}

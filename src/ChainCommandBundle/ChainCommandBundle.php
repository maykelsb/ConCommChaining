<?php

namespace ChainCommandBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass;


class ChainCommandBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CommandChainPass());
    }
}

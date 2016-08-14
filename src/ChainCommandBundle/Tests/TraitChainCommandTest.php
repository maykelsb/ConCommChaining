<?php
/**
 * This test is part of ChainCommandBundle test suite.
 */

namespace ChainCommandBundle\Tests;

use ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This trait implements some methods used by tests.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
trait TraitChainCommandTest
{
    /**
     * Loads the chain_foobar.yml file which declares the command chaining between foo and bar.
     *
     * @param ContainerBuilder $container
     * @return \ChainCommandBundle\Tests\DependencyInjection\Compiler\CommandChainPassTest
     */
    protected function chainCommands(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/Fixtures/Resources/config')
        );

        $loader->load('chain_foobar.yml');
        return $this;
    }

    /**
     * Calling CommandChainPass::process().
     *
     * @param ContainerBuilder $container
     * @return \ChainCommandBundle\Tests\DependencyInjection\Compiler\CommandChainPassTest
     */
    protected function process(ContainerBuilder $container)
    {
        $compilerPass = new CommandChainPass();
        $compilerPass->process($container);
        return $this;
    }
}


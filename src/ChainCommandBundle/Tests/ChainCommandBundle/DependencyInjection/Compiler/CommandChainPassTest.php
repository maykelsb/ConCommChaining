<?php
/**
 * This test is part of ChainCommandBundle test suite.
 */

namespace ChainCommandBundle\Tests\DependencyInjection\Compiler;

use ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Tests CommandChainPass verifying if the service substitution takes places.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class CommandChainPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing CommandChainPass::process().
     */
    public function testProcess()
    {
        // -- Configuring the container and processing it
        $container = new ContainerBuilder();
        $this->chainCommands($container)
            ->process($container);

        $this->assertTrue($container->hasDefinition('ccc.command.foo'));
        $this->assertTrue($container->hasDefinition('ccc.command.bar'));
        $this->assertTrue($container->hasDefinition(
            'ccc.command.foo' . CommandChainPass::MAINCOMM_POSFIX
        ));
        $this->assertTrue($container->hasDefinition(
            'ccc.command.bar' . CommandChainPass::CHAINEDCOMM_POSFIX
        ));
    }

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
            new FileLocator(__DIR__ . '/../../../Fixtures/Resources/config')
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

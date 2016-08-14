<?php
/**
 * This test is part of ChainCommandBundle test suite.
 */

namespace ChainCommandBundle\Tests\DependencyInjection\Compiler;

use ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ChainCommandBundle\Tests\TraitChainCommandTest;

/**
 * Tests CommandChainPass verifying if the service substitution takes places.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class CommandChainPassTest extends \PHPUnit_Framework_TestCase
{
    use TraitChainCommandTest;

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
            'ccc.command.foo'.CommandChainPass::MAINCOMM_POSFIX
        ));
        $this->assertTrue($container->hasDefinition(
            'ccc.command.bar'.CommandChainPass::CHAINEDCOMM_POSFIX
        ));
    }
}

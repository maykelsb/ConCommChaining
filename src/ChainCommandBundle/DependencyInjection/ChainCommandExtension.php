<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is a bundle extension to load this bundle services.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class ChainCommandExtension extends Extension
{
    /**
     * Loads @ChainCommandBundle/Resources/config/services.yml
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }

    /**
     * Loads @ChainCommandBundle/Tests/Fixtures/Resources/config/chain_foobar.yml for test environment.
     *
     * @param ContainerBuilder $container
     */
    protected function loadChain(ContainerBuilder $container)
    {
        if ('test' == $container->getParameter("kernel.environment")) {

            $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__ . '/../Tests/Fixtures/Resources/config')
            );

            $loader->load('chain_foobar.yml');
        }
    }
}

<?php
/**
 * This test is part of ChainCommandBundle test suite.
 */

namespace ChainCommandBundle\Tests\Command;

use ChainCommandBundle\Command\DummyCommand;
use ChainCommandBundle\Command\MasterCommand;
use ChainCommandBundle\Tests\Fixtures\Command\BarCommand;
use ChainCommandBundle\Tests\Fixtures\Command\FooCommand;
use ChainCommandBundle\Tests\TraitChainCommandTest;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests DummyCommand verifying its status and exit code.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class DummyCommandTest extends KernelTestCase
{
    use TraitChainCommandTest;

    public function testExecute()
    {
        self::bootKernel();

        $app = new Application(self::$kernel);
        $app->add(new DummyCommand());

        $comm = $app->find(DummyCommand::DUMMY_COMM_NAME);
        $commTester = new CommandTester($comm);
        $exitCode = $commTester->execute([
            'command' => $comm->getName()
        ]);

        $this->assertEquals(1, $exitCode, 'Returns %1 when called by its actual name.');
        $this->assertEquals(
            "This command is not intended to be called by its actual name.\n",
            $commTester->getDisplay()
        );
    }

//    public function testExecuteWhenHiddingAChainedCommand()
//    {
//        self::bootKernel();
//        $app = new Application(self::$kernel);
//        $app->add(new DummyCommand());
//        $app->add(new MasterCommand());
//        $app->add(new FooCommand());
//        $app->add(new BarCommand());
//
//        $contBuilder = new \Symfony\Component\DependencyInjection\ContainerBuilder();
//        $this->chainCommands($contBuilder)
//            ->process($contBuilder);
//
//        self::$kernel->getContainer()->set(
//            'ccc.command.foo',
//            $contBuilder->get('ccc.command.foo')
//        );
//        self::$kernel->getContainer()->set(
//            'ccc.command.bar',
//            $contBuilder->get('ccc.command.bar')
//        );
//        self::$kernel->getContainer()->set(
//            'ccc.command.foo' . \ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass::MAINCOMM_POSFIX,
//            $contBuilder->get('ccc.command.foo' . \ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass::MAINCOMM_POSFIX)
//        );
//        self::$kernel->getContainer()->set(
//            'ccc.command.bar' . \ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass::CHAINEDCOMM_POSFIX,
//            $contBuilder->get('ccc.command.bar' . \ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass::CHAINEDCOMM_POSFIX)
//        );
//
//        $chainedCommandName = 'ccc:bar';
//        $command = $app->find($chainedCommandName);
//
//        $commTester = new CommandTester($command);
//        $exitCode = $commTester->execute([
//            'command' => $command->getName()
//        ]);
//
//        $this->assertEquals(0, $exitCode, 'When hidding a chained command returns 0.');
//        $this->assertContains(
//            "Error: {$chainedCommandName} command is a member of",
//            $commTester->getDisplay()
//        );
//    }
}

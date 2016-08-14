<?php
/**
 * This test is part of ChainCommandBundle test suite.
 */

namespace ChainCommandBundle\Tests\Command;

use ChainCommandBundle\Command\DummyCommand;
use ChainCommandBundle\Command\MasterCommand;
use ChainCommandBundle\Tests\TraitChainCommandTest;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Tests DummyCommand verifying its status and exit code.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */
class MasterCommandTest extends KernelTestCase
{
    use TraitChainCommandTest;

    public function testExecute()
    {
        self::bootKernel();

        $app = new Application(self::$kernel);
        $app->add(new MasterCommand());

        $comm = $app->find(MasterCommand::MASTER_COMM_NAME);
        $commTester = new CommandTester($comm);
        $exitCode = $commTester->execute([
            'command' => $comm->getName(),
        ]);

        $this->assertEquals(1, $exitCode, 'Returns %1 when called by its actual name.');
        $this->assertEquals(
            "This command is not intended to be called by its actual name.\n",
            $commTester->getDisplay()
        );
    }
}

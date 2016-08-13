<?php

namespace ChainCommandBundle\Tests\Command;

use ChainCommandBundle\Command\DummyCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DummyCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel(['test']);
        $kernel->boot();

        $app = new Application($kernel);
        $app->add(new DummyCommand());

        $comm = $app->find('ccc:dummy');
        $commTester = new CommandTester($comm);
        $exitCode = $commTester->execute([
            'command' => $comm->getName()
        ]);

        $this->assertEquals(1, $exitCode, 'Returns 1 when called by its actual name.');
        $this->assertEquals(
            "This command is not intended to be called by its actual name.\n",
            $commTester->getDisplay()
        );
    }

//    public function testExecuteWhenHiddingAChainedCommand()
//    {
//        $kernel = $this->createKernel();
//        $kernel->registerBundles(function(){
//            return [
//                new FooBundle\FooBundle(),
//                new BarBundle\BarBundle()
//            ];
//        });
//
//
//        $kernel->boot();
//
//        $app = new Application($kernel);
//        $app->add(new DummyCommand());
//
//        $chainedCommandName = 'foo:hello';
//
//        $comm = $app->find($chainedCommandName);
//        $commTester = new CommandTester($comm);
//        $exitCode = $commTester->execute([
//            'command' => $comm->getName()
//        ]);
//
//        $this->assertEquals(0, $exitCode, 'When hidding a chained command returns 0.');
//        $this->assertContains(
//            "Error: {$chainedCommandName} command is a member of",
//            $commTester->getDisplay()
//        );
//    }
}


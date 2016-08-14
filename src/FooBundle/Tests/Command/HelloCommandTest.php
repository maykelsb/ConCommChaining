<?php

namespace FooBundle\Tests\Command;

use FooBundle\Command\HelloCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HelloCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $app = new Application($kernel);
        $app->add(new HelloCommand());

        $comm = $app->find('foo:hello');
        $commTester = new CommandTester($comm);
        $exitCode = $commTester->execute([
            'command' => $comm->getName()
        ]);

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertEquals("Hello from Foo!\n", $commTester->getDisplay());
    }
}

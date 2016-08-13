<?php

namespace Tests\FooBundle\Command;

use FooBundle\Command\HelloCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class HelloCommandTest extends KernelTestCase
{
    public function testGetName()
    {
        $command = new HelloCommand();
        $this->assertEquals('foo:hello', $command->getName());
    }

    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $app = new Application($kernel);
        $app->add(new HelloCommand());

        $comm = $app->find('foo:hello');
        $commTester = new CommandTester($comm);
        $commTester->execute([
            'command' => $comm->getName()
        ]);

        $output = $commTester->getDisplay();
        $this->assertEquals("Hello from Foo!\n", $output);
    }
}
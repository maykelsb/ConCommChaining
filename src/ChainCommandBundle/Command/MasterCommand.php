<?php
/**
 * This code is part of my solution to Console Command Chaining.
 *
 * @link https://github.com/mbessolov/test-tasks/blob/master/7.md
 */

namespace ChainCommandBundle\Command;

use ChainCommandBundle\DependencyInjection\Compiler\CommandChainPass;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * This is a master command which is used to call a chain of commands.
 *
 * When a command is tagged as a main chain command, that ordinary command is
 * switched with this command, so, it can be call the complete command chain.
 *
 * The switch between this class and the main command occurs in
 * CommandChainPass::activeMainCommandChain().
 *
 * @todo Convert to thin command
 */
class MasterCommand extends ContainerAwareCommand
{
    /**
     * The true name of DummyCommand.
     */
    const MASTER_COMM_NAME = 'ccc:master';

    /**
     * @var string The service id of the main command.
     */
    protected $mainCommand;

    /**
     * @var string[] The complete list of all commands chained to this maincommand.
     */
    protected $chainedCommands = [];

    /**
     * @var LoggerInterface Logger interface.
     */
    protected $logger;

    /**
     * @var StreamOutput Save output in memory to write in log later.
     */
    protected $outputMemory;

    /**
     * Returns a reference of LoggerInterface.
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if (is_null($this->logger)) {
            $this->logger = $this->getContainer()->get('monolog.logger.ccc');
        }

        return $this->logger;
    }

    /**
     * Defines the id of the command service.
     *
     * @param string $commandId Service id of main command.
     * @return MasterCommand
     */
    public function setMainCommand($commandId)
    {
        $this->mainCommand = $commandId;
        return $this;
    }

    /**
     * Defines the list of chained commands.
     *
     * @param string[] $chainedCommands List of chained commands.
     * @return MasterCommand
     */
    public function setChainedCommands(array $chainedCommands)
    {
        $this->chainedCommands = $chainedCommands;
        return $this;
    }

    /**
     * A basic configuration.
     */
    protected function configure() {
        $this->setName('ccc:master');
    }

    /**
     * Executes the main command and all its chained commands.
     *
     * First of all, the main command is found and executed. After that, all
     * commands in the chained list is called, one after another.
     *
     * @param InputInterface $input Common input interface.
     * @param OutputInterface $output Common output interface.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (self::MASTER_COMM_NAME == $this->getName()) {
            $output->writeln('This command is not intended to be called by its actual name.');
            return 1;
        }

        // -- Executing main command
        $this->logHeader();
        $this->executeCommand(
            $this->mainCommand . CommandChainPass::MAINCOMM_POSFIX,
            $input,
            $output
        );

        // -- Executing chain members
        $this->getLogger()->info("Executing {$this->getName()} chain members:");
        foreach ($this->chainedCommands as $commandInfo) {
            $this->executeCommand(
                $commandInfo['serviceid'] . CommandChainPass::CHAINEDCOMM_POSFIX,
                $input,
                $output
            );
        }
        $this->getLogger()->info("Execution of {$this->getName()} chain completed.");
    }

    /**
     * Execute a command and stores its output in log before show it on screen.
     *
     * @param string $serviceId Service id to be called.
     * @param InputInterface $input Common input interface.
     * @param OutputInterface $output Common output interface.
     */
    protected function executeCommand(
        $serviceId,
        InputInterface $input,
        OutputInterface $output
    ) {
        // -- Storing ouput of the command in memory
        $memoryOutput = new StreamOutput(fopen('php://memory', 'w', false));
        $this->getContainer()->get($serviceId)->execute($input, $memoryOutput);

        // -- Preparing the memory buffer to read the last message
        rewind($memoryOutput->getStream());

        // -- Logging the last message
        $this->getLogger()->info($text = stream_get_contents($memoryOutput->getStream()));

        // -- Writing it on screen
        $output->writeln(trim($text));
    }

    /**
     * Generating log header.
     */
    protected function logHeader()
    {
        $this->getLogger()->info("{$this->getName()} is a master command of a command"
            . " chain that has registered member commands");
        foreach ($this->chainedCommands as $commandInfo)
        {
            $this->getLogger()->info("{$commandInfo['commandname']} registered as a member of "
                . "{$this->getName()} command chain");
        }
        $this->getLogger()->info("Executing {$this->getName()} command itself first:");
    }

}

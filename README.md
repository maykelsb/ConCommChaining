# ConCommChaining
Or Console Command Chaining. This bundle is my solution to the [Console Command Chaining](https://github.com/mbessolov/test-tasks/blob/master/7.md)
test issued by mbessolov.
Requirement list:
* Allow console commands to be chained;
* Chained commands must not be called outside its chain;
* All chained executions must be logged with a custom format;
* Unitary and functional tests must be provided;
* PSR-2 and [Symfony Code Standards](http://symfony.com/doc/current/contributing/code/standards.html) must be followed;
* All code should be documented with phpdoc.

In a few words, this test asks for a bundle which allow users to chain console commands. Chained command cannot be
called from outside its chain. All chained executions must be logged and all code should have its test suite.

## Solution
This solution relies on services. Master and chained command services are manipulated at compiler pass, and custom services are generated
to fulfill the requirements.
This are the base commands:
* **ChainedCommand**: Each chained service command is substituted by one of this. The new ChainedCommand receives the Command name of its predecessor and is called in its place. It just issues a message informing that it could not be called outside its chain.
* **MasterCommand**: Each service command with a command chain is substituted by one of this. It register its main command and chained commands, when it is called, all of them is called.

### Service changing flow
Imagine this set of commands:
* foo:hello (foo.hello_command)
* bar:hi (bar.hi_command)

If you register bar:hi as a chained command of foo:hello this is what happens behind courtains:

+ bar.hi_command definition is cloned and registered as bar.hi_command_chained.
+ bar.hi_command definition has its class switched to DummyCommand class and has his command name modified to bar:hi.
+ foo.hello_command definition is cloned and registered as foo.hello_command_main.
+ foo.hello_command definition has its class switched to MasterCommand class and has his command name modified to foo:hello.
+ foo.hello_command, now a MasterCommand, load foo.hello_command_main as its main command and bar.hi_command_chained as its command chain.

After that, every time that foo:hello is called, a MasterCommand is executed and run its main command and its chained commands. And, every time
that bar:hi command is called, an error message is issued by its DummyCommand command.
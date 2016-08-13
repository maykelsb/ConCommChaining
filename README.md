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
* ChainedCommand: Each chained service command is substituted by one of this. The new ChainedCommand receives the Command name of its predecessor and is called in its place. It just issues a message informing that it could not be called outside its chain.
* MasterCommand: Each service command with a command chain is substituted by one of this. It register its main command and chained commands, when it is called, all of them is called.
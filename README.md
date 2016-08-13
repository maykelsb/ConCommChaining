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

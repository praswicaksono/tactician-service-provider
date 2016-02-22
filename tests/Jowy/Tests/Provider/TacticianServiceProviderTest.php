<?php

namespace Jowy\Tests\Provider;

use Jowy\Tests\Stub\TestCommand;
use Jowy\Tests\Stub\TestHandler;
use Jowy\Tests\Stub\TestMiddleware;
use League\Tactician\Plugins\LockingMiddleware;
use Silex\Application;
use Silex\Provider\TacticianServiceProvider;

/**
 * Class TacticianServiceProviderTest
 * @package Jowy\Tests\Provider
 */
class TacticianServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * return void
     */
    public function testRegisterDefaultImplementation()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'class_name',
                'tactician.middleware' =>
                    [
                        new LockingMiddleware()
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);
    }

    /**
     * @return Application
     */
    protected function init()
    {
        $container = new Application();

        return $container;
    }

    /**
     * return void
     */
    public function testClassNameLocator()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'class_name',
                'tactician.middleware' =>
                    [
                        new LockingMiddleware()
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);

        $container[TestHandler::class] = function () {
            return new TestHandler();
        };

        $container['tactician.locator']->addHandler(TestCommand::class, TestHandler::class);

        $command = new TestCommand();
        $command->param = 'jowy';

        $this->assertEquals($container['tactician.command_bus']->handle($command), 'jowy');
    }

    /**
     * return @void
     */
    public function testClassNameLocatorWithoutSuffix()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'class_name_without_suffix',
                'tactician.middleware' =>
                    [
                        new LockingMiddleware()
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);

        $container[TestHandler::class] = function () {
            return new TestHandler();
        };

        $container['tactician.locator']->addHandler(TestCommand::class, TestHandler::class);

        $command = new TestCommand();
        $command->param = 'jowy';

        $this->assertEquals($container['tactician.command_bus']->handle($command), 'jowy');
    }

    /**
     * return void
     */
    public function testHandleLocator()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'handle',
                'tactician.middleware' =>
                    [
                        new LockingMiddleware()
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);

        $container[TestHandler::class] = function () {
            return new TestHandler();
        };

        $container['tactician.locator']->addHandler(TestCommand::class, TestHandler::class);

        $command = new TestCommand();
        $command->param = 'jowy';

        $this->assertEquals($container['tactician.command_bus']->handle($command), 'jowy');
    }

    /**
     * return void
     */
    public function testInvokeLocator()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'invoke',
                'tactician.middleware' =>
                    [
                        new LockingMiddleware()
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);

        $container[Testhandler::class] = function () {
            return new TestHandler();
        };

        $container['tactician.locator']->addHandler(TestCommand::class, TestHandler::class);

        $command = new TestCommand();
        $command->param = 'jowy';

        $this->assertEquals($container['tactician.command_bus']->handle($command), 'jowy');
    }

    /**
     * return void
     */
    public function testMiddleware()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'invoke',
                'tactician.middleware' =>
                    [
                        new TestMiddleware()
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);

        $container[TestHandler::class] = function () {
            return new TestHandler();
        };

        $container['tactician.locator']->addHandler(TestCommand::class, TestHandler::class);

        $command = new TestCommand();
        $command->param = 'jowy';

        $this->assertEquals($container['tactician.command_bus']->handle($command), 'middleware');
    }

    /**
     * return void
     */
    public function testLazyMiddleware()
    {
        $container = $this->init();

        $container[TestMiddleware::class] = function () {
            return new TestMiddleware();
        };

        $container->register(
            new TacticianServiceProvider([
                'tactician.inflector' => 'invoke',
                'tactician.middleware' =>
                    [
                        TestMiddleware::class
                    ]
            ])
        );

        $this->assertInstanceOf('League\Tactician\CommandBus', $container['tactician.command_bus']);

        $container[TestHandler::class] = function () {
            return new TestHandler();
        };

        $container['tactician.locator']->addHandler(TestCommand::class, TestHandler::class);

        $command = new TestCommand();
        $command->param = 'jowy';

        $this->assertEquals($container['tactician.command_bus']->handle($command), 'middleware');
    }
}

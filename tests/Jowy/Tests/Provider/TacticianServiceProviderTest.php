<?php


namespace Jowy\Tests\Provider;

use Jowy\Tests\Stub\TestCommand;
use Jowy\Tests\Stub\TestHandler;
use Jowy\Tests\Stub\TestMiddleware;
use League\Tactician\Plugins\LockingMiddleware;
use Pimple\Container;
use Silex\Provider\TacticianServiceProvider;

/**
 * Class TacticianServiceProviderTest
 * @package Jowy\Tests\Provider
 */
class TacticianServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Container
     */
    protected function init()
    {
        $container = new Container();

        return $container;
    }

    /**
     * return void
     */
    public function testRegisterDefaultImplementation()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider($container),
            [
                "tactician.inflector" => "class_name",
                "tactician.middlewares" => function() {
                    return new LockingMiddleware();
                }
            ]
        );

        $this->assertInstanceOf("League\\Tactician\\CommandBus", $container["command.bus"]);
    }

    public function testClassNameLocator()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider(),
            [
                "tactician.inflector" => "class_name",
                "tactician.middlewares" =>
                    [
                        function() {
                            return new LockingMiddleware();
                        }
                    ]
            ]
        );

        $this->assertInstanceOf("League\\Tactician\\CommandBus", $container["command.bus"]);

        $container["app.handler.TestCommand"] = function () {
            return new TestHandler();
        };

        $command = new TestCommand();
        $command->param = "jowy";

        $this->assertEquals($container["command.bus"]->handle($command), "jowy");
    }

    public function testHandleLocator()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider(),
            [
                "tactician.inflector" => "handle",
                "tactician.middlewares" =>
                    [
                        function() {
                            return new LockingMiddleware();
                        }
                    ]
            ]
        );

        $this->assertInstanceOf("League\\Tactician\\CommandBus", $container["command.bus"]);

        $container["app.handler.TestCommand"] = function () {
            return new TestHandler();
        };

        $command = new TestCommand();
        $command->param = "jowy";

        $this->assertEquals($container["command.bus"]->handle($command), "jowy");
    }

    public function testInvokeLocator()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider(),
            [
                "tactician.inflector" => "invoke",
                "tactician.middlewares" =>
                    [
                        function() {
                            return new LockingMiddleware();
                        }
                    ]
            ]
        );

        $this->assertInstanceOf("League\\Tactician\\CommandBus", $container["command.bus"]);

        $container["app.handler.TestCommand"] = function () {
            return new TestHandler();
        };

        $command = new TestCommand();
        $command->param = "jowy";

        $this->assertEquals($container["command.bus"]->handle($command), "jowy");
    }

    public function testMiddleware()
    {
        $container = $this->init();

        $container->register(
            new TacticianServiceProvider(),
            [
                "tactician.inflector" => "invoke",
                "tactician.middlewares" =>
                    [
                        function() {
                            return new TestMiddleware();
                        }
                    ]
            ]
        );

        $this->assertInstanceOf("League\\Tactician\\CommandBus", $container["command.bus"]);

        $container["app.handler.TestCommand"] = function () {
            return new TestHandler();
        };

        $command = new TestCommand();
        $command->param = "jowy";

        $this->assertEquals($container["command.bus"]->handle($command), "middleware");
    }
}

// EOF

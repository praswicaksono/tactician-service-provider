<?php

namespace Silex\Provider;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\InvokeInflector;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Component\Tactician\Locator\Silex;

/**
 * Class TacticianServiceProvider
 * @package Silex\Provider
 */
class TacticianServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app["command.bus"] = function () use ($app) {
            switch ($app["tactician.inflector"]) {
                case "class_name":
                    $inflector = new HandleClassNameInflector();
                    break;
                case "handle":
                    $inflector = new HandleInflector();
                    break;
                case "invoke":
                    $inflector = new InvokeInflector();
                    break;
                default:
                    $inflector = new HandleClassNameInflector();
                    break;
            }

            $handler_middleware = new CommandHandlerMiddleware(
                new Silex($app),
                $inflector
            );

            $middlewares = $app["tactician.middlewares"];
            array_push($middlewares, $handler_middleware);

            return new CommandBus($middlewares);
        };
    }
}

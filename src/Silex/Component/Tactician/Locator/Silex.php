<?php

namespace Silex\Component\Tactician\Locator;

use League\Tactician\Handler\Locator\HandlerLocator;
use Pimple\Container;

/**
 * Class Silex
 * @package Silex\Component\Tactician\Locator
 */
class Silex implements HandlerLocator
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->app['tactician.handler_map'] = new \ArrayObject();
    }

    /**
     * @param $command
     * @return mixed
     */
    public function getHandlerForCommand($commandClassName)
    {
        $handlerClassName = $this->app['tactician.handler_map'][$commandClassName];

        $handlerObject = $this->app[$handlerClassName];

        if ( ! is_object($handlerObject)) {
            throw new \InvalidArgumentException(sprintf(
                'Class %s is not registered in container',
                $handlerClassName
            ));
        }

        return $this->app[$handlerClassName];
    }

    /**
     * @param string $commandClassName
     * @param string $handlerClassName
     */
    public function addHandler($commandClassName, $handlerClassName)
    {
        if ( ! class_exists($handlerClassName)) {
            throw new \InvalidArgumentException(sprintf(
                'Handler class %s not found',
                $handlerClassName
            ));
        }

        $this->app['tactician.handler_map'][$commandClassName] = $handlerClassName;
    }
}

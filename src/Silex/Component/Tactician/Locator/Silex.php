<?php


namespace Silex\Component\Tactician\Locator;

use League\Tactician\Command;
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
    }

    /**
     * @param Command $command
     * @return mixed
     */
    public function getHandlerForCommand(Command $command)
    {
        $handler_id = "app.handler." . join("", array_slice(explode("\\", get_class($command)), -1));
        return $this->app[$handler_id];
    }
}

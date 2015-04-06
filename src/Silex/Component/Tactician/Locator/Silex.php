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
    }

    /**
     * @param $command
     * @return mixed
     */
    public function getHandlerForCommand($command)
    {
        $handler_id = "app.handler." . $command;
        return $this->app[$handler_id];
    }
}

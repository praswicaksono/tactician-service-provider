<?php


namespace Jowy\Tests\Stub;

use League\Tactician\Middleware;

/**
 * Class TestMiddleware
 * @package Jowy\Tests\Stub
 */
class TestMiddleware implements Middleware
{
    /**
     * @param $command
     * @param callable $next
     * @return string
     */
    public function execute($command, callable $next)
    {
        return "middleware";
    }
}

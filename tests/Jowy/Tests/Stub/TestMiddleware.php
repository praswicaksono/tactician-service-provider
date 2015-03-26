<?php


namespace Jowy\Tests\Stub;

use League\Tactician\Command;
use League\Tactician\Middleware;

/**
 * Class TestMiddleware
 * @package Jowy\Tests\Stub
 */
class TestMiddleware implements Middleware
{
    /**
     * @param Command $command
     * @param callable $next
     * @return string
     */
    public function execute(Command $command, callable $next)
    {
        return "middleware";
    }
}

// EOF

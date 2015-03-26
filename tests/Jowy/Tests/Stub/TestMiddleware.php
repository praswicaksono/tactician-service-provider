<?php


namespace Jowy\Tests\Stub;

use League\Tactician\Command;
use League\Tactician\Middleware;

class TestMiddleware implements Middleware
{
    public function execute(Command $command, callable $next)
    {
        return "middleware";
    }
}

// EOF

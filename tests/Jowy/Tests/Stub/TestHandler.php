<?php


namespace Jowy\Tests\Stub;

use League\Tactician\Command;

class TestHandler
{
    public function handle(Command $command)
    {
        return $command->param;
    }

    public function handleTestCommand(Command $command)
    {
        return $command->param;
    }

    public function __invoke(Command $command)
    {
        return $command->param;
    }
}

// EOF

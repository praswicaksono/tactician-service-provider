<?php


namespace Jowy\Tests\Stub;

use League\Tactician\Command;

/**
 * Class TestHandler
 * @package Jowy\Tests\Stub
 */
class TestHandler
{
    /**
     * @param Command $command
     * @return mixed
     */
    public function handle(Command $command)
    {
        return $command->param;
    }

    /**
     * @param Command $command
     * @return mixed
     */
    public function handleTestCommand(Command $command)
    {
        return $command->param;
    }

    /**
     * @param Command $command
     * @return mixed
     */
    public function __invoke(Command $command)
    {
        return $command->param;
    }
}

// EOF

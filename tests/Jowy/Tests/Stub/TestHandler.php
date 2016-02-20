<?php

namespace Jowy\Tests\Stub;

/**
 * Class TestHandler
 * @package Jowy\Tests\Stub
 */
class TestHandler
{
    /**
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        return $command->param;
    }

    /**
     * @param $command
     * @return mixed
     */
    public function handleTestCommand($command)
    {
        return $command->param;
    }

    /**
     * @param $command
     * @return mixed
     */
    public function handleTest($command)
    {
        return $command->param;
    }

    /**
     * @param $command
     * @return mixed
     */
    public function __invoke($command)
    {
        return $command->param;
    }
}

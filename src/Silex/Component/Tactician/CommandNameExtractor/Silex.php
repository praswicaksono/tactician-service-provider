<?php


namespace Silex\Component\Tactician\CommandNameExtractor;

use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;

/**
 * Class Silex
 * @package Silex\Component\Tactician\CommandNameExtractor
 */
class Silex implements CommandNameExtractor
{
    /**
     * @param object $command
     * @return string
     */
    public function extract($command)
    {
        if (! is_object($command)) {
            throw new \InvalidArgumentException("Command must be an object");
        }

        return join("", array_slice(explode("\\", get_class($command)), -1));
    }
}


<?php

namespace Eoko\Wpscan\Command;

use Eoko\Wpscan\Command\Args\ArgsInterface;
use Eoko\Wpscan\Command\Args\NoColor;

class Command
{
    protected $args = [];

    /**
     * Command constructor.
     */
    public function __construct()
    {
        $this->addArgs(new NoColor());
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    public function addArgs(ArgsInterface $arg)
    {
        $this->args[get_class($arg)] = $arg;
        return $this;
    }

    public function __toString()
    {
        return implode(' ', $this->args);
    }
}

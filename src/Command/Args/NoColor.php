<?php

namespace Eoko\Wpscan\Command\Args;

class NoColor implements ArgsInterface
{
    public function __toString()
    {
        return '--no-color';
    }
}

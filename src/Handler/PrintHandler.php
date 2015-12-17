<?php

namespace Eoko\Wpscan\Handler;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Command;

class PrintHandler implements HandlerInterface
{
    public function handle($s, Client $client, Command $cmd)
    {
        echo trim($s) . "\n";
    }

    public function getResult()
    {
    }

    public function __toString()
    {
        return '';
    }
}

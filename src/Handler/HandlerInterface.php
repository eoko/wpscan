<?php

namespace Eoko\Wpscan\Handler;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Command;

interface HandlerInterface
{
    public function handle($s, Client $client, Command $cmd);

    public function getResult();

    public function __toString();
}

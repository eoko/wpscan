<?php

namespace Eoko\Wpscan\Handler;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Command;

class UrlHandler implements HandlerInterface
{
    protected $pattern = '/[+] URL: /';

    protected $url;

    public function handle($line, Client $client, Command $cmd)
    {
        $text = trim($line);
        if (preg_match(preg_quote($this->pattern), $line)) {
            $this->url = substr($text, strlen($this->pattern) - 2);
            $client->removeHandler(self::class);
        }
    }

    public function getResult()
    {
        return $this->url;
    }

    public function __toString()
    {
        return 'Url : ' . $this->url . "\n";
    }
}

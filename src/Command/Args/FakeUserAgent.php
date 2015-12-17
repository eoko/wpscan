<?php

namespace Eoko\Wpscan\Command\Args;

class FakeUserAgent implements ArgsInterface
{
    public function __toString()
    {
        return '--user-agent "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)"';
    }
}

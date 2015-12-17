<?php

namespace Eoko\Wpscan\Handler;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Command;
use Eoko\Wpscan\Test\BaseTest;

class PluginHandlerTest extends BaseTest
{

    public function testHandler()
    {
        $handler = new PluginHandler();
        $client = \Mockery::mock(Client::class);
        $command = \Mockery::mock(Command::class);

        foreach ($this->getSampleArray() as $line) {
            $handler->handle($line, $client, $command);
        }

        $result = $handler->getResult();
        $this->assertEmpty($result[0]['warning']);
        $this->assertEquals("v1.08", $result[0]['version']);
        $this->assertEquals("smartstart", $result[0]['name']);
        $this->assertEquals("http://acme.fr/wp-content/themes/smartstart/", $result[0]['location']);
        $this->assertEquals("Smart Start - VideoJS Cross-Site Scripting Vulnerability", $result[0]['error'][0]['name']);
        $this->assertEquals("exploit", $result[0]['error'][0]['type']);
        $this->assertEquals(3, count($result[0]['error'][0]['references']));
    }
}

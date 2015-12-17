<?php

namespace Eoko\Wpscan\Test;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function getSample()
    {
        return file_get_contents(__DIR__ . '/sample/output');
    }

    protected function getSampleArray()
    {
        return explode("\n", $this->getSample());
    }
}

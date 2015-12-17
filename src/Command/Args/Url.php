<?php

namespace Eoko\Wpscan\Command\Args;

class Url implements ArgsInterface
{
    protected $url;

    /**
     * Url constructor.
     * @param $url
     */
    public function __construct($url = null)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function __toString()
    {
        return '-u ' . $this->getUrl();
    }
}

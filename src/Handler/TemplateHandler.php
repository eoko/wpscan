<?php

namespace Eoko\Wpscan\Handler;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Command;

class TemplateHandler implements HandlerInterface
{
    protected $pattern = '/WordPress theme in use/';
    protected $namePattern = '/[+] Name:/';
    protected $locationPattern = '/|  Location: /';
    protected $stylePattern = '/|  Style URL: /';
    protected $themeNamePattern = '/|  Theme Name: /';
    protected $themeUriPattern = '/|  Theme URI: /';
    protected $descriptionPattern = '/|  Description: /';
    protected $authorPattern = '/|  Author: /';
    protected $authorUriPattern = '/|  Author URI: /';

    protected $blockDetected = false;

    protected $theme = [];

    protected $found = false;

    public function handle($line, Client $client, Command $cmd)
    {
        if (preg_match(preg_quote($this->pattern), $line)) {
            $this->found = true;
        }

        if ($this->found) {
            if (preg_match(preg_quote($this->namePattern), $line)) {
                $name = explode(' - ', substr($line, 10));
                if ($name[0]) {
                    $this->theme['name'] = $name[0];
                }
                if ($name[1]) {
                    $this->theme['version'] = $name[1];
                }
                $this->blockDetected = true;
            } elseif ($this->blockDetected == true) {
                if (preg_match(preg_quote($this->locationPattern), $line)) {
                    $this->theme['location'] = substr($line, 14);
                    return;
                }

                if (preg_match(preg_quote($this->stylePattern), $line)) {
                    $this->theme['style'] = substr($line, 15);
                    return;
                }

                if (preg_match(preg_quote($this->themeNamePattern), $line)) {
                    $this->theme['theme_name'] = substr($line, 16);
                    return;
                }

                if (preg_match(preg_quote($this->themeUriPattern), $line)) {
                    $this->theme['theme_uri'] = substr($line, 15);
                    return;
                }

                if (preg_match(preg_quote($this->authorPattern), $line)) {
                    $this->theme['author'] = substr($line, 12);
                    return;
                }

                if (preg_match(preg_quote($this->authorUriPattern), $line)) {
                    $this->theme['author_uri'] = substr($line, 16);
                    return;
                }

                if (preg_match(preg_quote($this->descriptionPattern), $line)) {
                    $this->theme['description'] = substr($line, 17);
                    return;
                }

                $client->removeHandler(self::class);
            }
        }
    }

    public function getResult()
    {
        return $this->theme;
    }

    public function __toString()
    {
        $str = 'Template ';
        $theme = $this->theme;

        if (isset($theme['name'])) {
            $str .= '   name : ' . $theme['name'] . "\n";
            if (isset($theme['version'])) {
                $str .= '      + version : ' . $theme['version'] . "\n";
            }
        }

        return $str;
    }
}

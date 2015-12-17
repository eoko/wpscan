<?php

namespace Eoko\Wpscan\Handler;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Command;

class PluginHandler implements HandlerInterface
{
    protected $pluginsFoundpattern = '/plugins found:/';
    protected $endPattern = '/[+] Finished:/';
    protected $pluginPattern = '/[+] Name: /';
    protected $locationPattern = '/ |  Location:/';
    protected $readmePattern = '/ |  Readme:/';
    protected $outOfDatePattern = '/The version is out of date/';
    protected $exploitPattern = '/[!] Title:/';
    protected $referencePattern = '/Reference: /';
    protected $fixedInPattern = '/[i] Fixed in: /';

    protected $popularPluginsPattern = '/[+] Enumerating installed plugins (only ones marked as popular) .../';
    protected $allPluginsPattern = '/[+] Enumerating all plugins (may take a while and use a lot of system resources) .../';

    protected $pluginFound = false;
    protected $textBlock = false;
    protected $lastPlugin;
    protected $lastExploit;

    protected $plugins = [];

    public function handle($line, Client $client, Command $cmd)
    {
        if (preg_match(preg_quote($this->popularPluginsPattern), $line)
            || preg_match(preg_quote($this->allPluginsPattern), $line)
        ) {
            $this->textBlock = true;
        };

        if (!$this->pluginFound) {
            if (preg_match(preg_quote($this->pluginsFoundpattern), $line)) {
                $this->pluginFound = true;
                if ($this->textBlock) {
                    $lines = explode("\n", $line);
                    foreach ($lines as $line) {
                        $this->processLine($line);
                    }
                }
            }
        }

        if (preg_match($this->endPattern, $line)) {
            $client->removeHandler(self::class);
            return;
        }

        if (!$this->textBlock) {
            $this->processLine($line);
        }
    }

    protected function processLine($line)
    {
        if (preg_match(preg_quote($this->pluginPattern), $line)) {
            $text = substr($line, 10);
            $plugin = explode(' - ', $text);

            $p = ['warning' => [], 'error' => [], 'version' => 'unknown'];
            if (isset($plugin[0])) {
                $p['name'] = trim($plugin[0]);
            }
            if (isset($plugin[1])) {
                $p['version'] = trim($plugin[1]);
            }

            $this->plugins[] = $p;

            end($this->plugins);
            $this->lastPlugin = key($this->plugins);
        }

        if (preg_match(preg_quote($this->locationPattern), $line)) {
            $this->plugins[$this->lastPlugin]['location'] = substr($line, 14);
        }

        if (preg_match(preg_quote($this->readmePattern), $line)) {
            $this->plugins[$this->lastPlugin]['readme'] = substr($line, 12);
        }

        if (preg_match(preg_quote($this->outOfDatePattern), $line)) {
            $this->plugins[$this->lastPlugin]['warning'][] = [
                'type' => 'outOfDate',
                'msg' => trim(substr($line, 4)),
            ];
        }

        if (preg_match(preg_quote($this->exploitPattern), $line)) {
            $this->plugins[$this->lastPlugin]['error'][] = ['name' => trim(substr($line, 11)), 'type' => 'exploit', 'references' => []];

            end($this->plugins[$this->lastPlugin]['error']);
            $this->lastExploit = key($this->plugins[$this->lastPlugin]['error']);
        }

        if (preg_match(preg_quote($this->referencePattern), $line)) {
            $this->plugins[$this->lastPlugin]['error'][$this->lastExploit]['references'][] = [
                'url' => trim(substr($line, 15)),
            ];
        }
        if (preg_match(preg_quote($this->fixedInPattern), $line)) {
            $this->plugins[$this->lastPlugin]['error'][$this->lastExploit]['fixedIn'] = trim(substr($line, 14));
        }
    }

    public function getResult()
    {
        return $this->plugins;
    }

    public function __toString()
    {
        $str = 'Plugins ' . "\n";
        foreach ($this->plugins as $plugin) {
            $str .= '   name : ' . $plugin['name'] . "\n";
            $str .= '       + version : ' . $plugin['version'] . "\n";
            $str .= '       + warn : ' . "\n";
            foreach ($plugin['warning'] as $warn) {
                $str .= '            > ' . $warn['msg'] . "\n";
            }
            foreach ($plugin['error'] as $err) {
                $str .= '            > ' . $err['name'] . "\n";
            }
        }
        return $str;
    }
}

<?php

namespace Eoko\Wpscan\Client;

use Eoko\Wpscan\Command\Command;
use Eoko\Wpscan\Handler\HandlerInterface;
use Zend\Log\Logger;

class Client
{
    protected $bin = 'wpscan';

    /** @var  HandlerInterface[] */
    protected $handler = [];

    /** @var  HandlerInterface[] */
    protected $ignoredHandler = [];

    /** @var Logger */
    protected $logger;

    /**
     * @param $bin
     * @param Logger|null $logger
     */
    public function __construct($bin, Logger $logger)
    {
        $this->bin = $bin;
        $this->logger = $logger;
    }

    public function addHandler(HandlerInterface $handler)
    {
        $this->handler[get_class($handler)] = $handler;
    }

    public function removeHandler($className)
    {
        if (isset($this->handler[$className])) {
            $this->ignoredHandler[] = $this->handler[$className];
            unset($this->handler[$className]);
        }
    }

    public function run(Command $cmd)
    {
        $command = $this->bin . ' ' . (string)$cmd;

        $this->logger->debug('Command : `' . $command . '``');
        $proc = popen((string)$command, 'r');

        while (($line = fgets($proc)) !== false) {
            foreach ($this->handler as $handler) {
                $handler->handle($line, $this, $cmd);
            }
        }

        $this->logger->info('Done.');

        /** @var HandlerInterface[] $handlers */
        return array_merge($this->handler, $this->ignoredHandler);
    }
}

<?php

namespace Eoko\Wpscan\Controller;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Command\Args\FakeUserAgent;
use Eoko\Wpscan\Command\Args\Url;
use Eoko\Wpscan\Command\Command;
use Eoko\Wpscan\Handler\PluginHandler;
use Eoko\Wpscan\Handler\TemplateHandler;
use Eoko\Wpscan\Handler\UrlHandler;
use Zend\Log\Logger;
use Zend\Mvc\Controller\AbstractConsoleController;

class IndexController extends AbstractConsoleController
{
    /** @var  Client */
    protected $client;

    /** @var  Logger */
    protected $logger;

    /**
     * IndexController constructor.
     * @param Client $client
     * @param Logger $logger
     */
    public function __construct(Client $client, Logger $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function showAction()
    {
        $url = $this->getEvent()->getRouteMatch()->getParam('url');
        $this->logger->info('Preparing scan for `' . $url . '`');

        $client = $this->client;
        $client->addHandler(new UrlHandler());
        $client->addHandler(new PluginHandler());
        $client->addHandler(new TemplateHandler());

        $command = new Command();
        $command->addArgs(new Url($url));
        $command->addArgs(new FakeUserAgent());

        $result = $client->run($command);

        foreach ($result as $handler) {
            $this->console->writeLine($handler->__toString());
        }
    }
}

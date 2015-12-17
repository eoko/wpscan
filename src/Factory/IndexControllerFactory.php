<?php

namespace Eoko\Wpscan\Factory;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Controller\IndexController;
use Zend\Log\Logger;
use Zend\Log\Writer\Noop;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Client $client */
        $client = $serviceLocator->getServiceLocator()->get(Client::class);
        $config = $serviceLocator->getServiceLocator()->get('Config')['wpscan'];

        if (isset($config['logger']) && is_string($config['logger']) && $serviceLocator->getServiceLocator()->has($config['logger'])) {
            /** @var Logger $logger */
            $logger = $serviceLocator->getServiceLocator()->get($config['logger']);
        } else {
            $logger = new Logger();
            $logger->addWriter(new Noop());
        }

        return new IndexController($client, $logger);
    }
}

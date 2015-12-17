<?php

namespace Eoko\Wpscan\Factory;

use Eoko\Wpscan\Client\Client;
use Zend\Log\Logger;
use Zend\Log\Writer\Noop;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClientFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['wpscan'];
        $logger = null;
        $bin = $config['bin'];

        if (isset($config['logger']) && is_string($config['logger']) && $serviceLocator->has($config['logger'])) {
            $logger = $serviceLocator->get($config['logger']);
        } else {
            $logger = new Logger();
            $logger->addWriter(new Noop());
        }

        return new Client($bin, $logger);
    }
}

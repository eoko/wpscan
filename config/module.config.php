<?php

namespace Eoko\Wpscan;

use Eoko\Wpscan\Client\Client;
use Eoko\Wpscan\Controller\IndexController;
use Eoko\Wpscan\Factory\ClientFactory;
use Eoko\Wpscan\Factory\IndexControllerFactory;

return [
    'wpscan' => [
        'bin' => 'wpscan',
    ],

    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Client::class => ClientFactory::class,
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'show-users' => [
                    'options' => [
                        'route' => 'wpscan show <url>',
                        'defaults' => [
                            'controller' => IndexController::class,
                            'action' => 'show'
                        ],
                    ],
                ],
            ],
        ],
    ],
];

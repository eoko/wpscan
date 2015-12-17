# Eoko\Wpscan Module for ZF2
[![Build Status](https://travis-ci.org/eoko/wpscan.svg)](https://travis-ci.org/eoko/wpscan)
[![Latest Stable Version](https://poser.pugx.org/eoko/wpscan/v/stable)](https://packagist.org/packages/eoko/wpscan)
[![Total Downloads](https://poser.pugx.org/eoko/wpscan/downloads)](https://packagist.org/packages/eoko/wpscan)
[![Latest Unstable Version](https://poser.pugx.org/eoko/wpscan/v/unstable)](https://packagist.org/packages/eoko/wpscan)
[![License](https://poser.pugx.org/eoko/wpscan/license)](https://packagist.org/packages/eoko/wpscan)

This module is highly experimental and used for a wpscan sandbox with ZF2.

## Config

See `config/wpscan.local.php.dist`.

## Demo Controller

You can use this module by calling the demo controller : `php public/index.php wpscan show http://acme.com`.

## Usage

```PHP
$client = $serviceLocator->get(Client::class);
$command = new Command();
$command->addArg(new FakeUserAgent());
$client->addHandler(new PluginHandler());
$result = $client->run($command);
```
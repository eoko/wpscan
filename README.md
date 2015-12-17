# Eoko\Wpscan Module for ZF2

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
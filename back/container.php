<?php

declare(strict_types=1);

use Noodlehaus\Config;

$builder = new \DI\ContainerBuilder();

$builder->enableCompilation(__DIR__ . '/tmp');
$builder->writeProxiesToFile(true, __DIR__ . '/tmp/proxies');
$builder->useAutowiring(true);

$builder->addDefinitions([
    'OpenWeatherMapApiClient\Commands\*Command' => \DI\autowire()
        ->constructorParameter('config', new Config(__DIR__ . '/OpenWeatherMapApiClient/requests.yml')),
    \OpenWeatherMapApiClient\Client::class      => \DI\autowire()
        ->constructorParameter('apiKey', getenv('OPEN_WEATHER_MAP_API_KEY')),

    'CbrApiClient\Commands\*Command' => \DI\autowire()
        ->constructorParameter('config', new Config(__DIR__ . '/CbrApiClient/requests.yml')),

    \Services\TestAssignmentService::class => \DI\autowire()
        ->constructorParameter('codes', (new Config(__DIR__ . '/Services/config.yml'))->get('currencies_char_codes', [])),
]);

return $builder->build();
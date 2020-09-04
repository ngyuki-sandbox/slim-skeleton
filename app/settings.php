<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;
use function DI\env;
use function DI\get;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        'debug' => env('APP_DEBUG', false),
        'settings' => [
            'displayErrorDetails' => get('debug'),
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'twig' => [
                'debug' => get('debug'),
                'strict_variables' => true,
                'cache' => __DIR__ . '/../var/cache/twig',
            ],
        ],
    ]);
};

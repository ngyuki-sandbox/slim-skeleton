<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        Twig::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            return Twig::create(__DIR__ . '/../templates', $settings['twig']);
        },

        App::class => function (ContainerInterface $container) {
            return AppFactory::createFromContainer($container);
        },

        Guard::class => function (App $app) {
            return (new Guard($app->getResponseFactory()))->setPersistentTokenMode(true);
        },
    ]);
};

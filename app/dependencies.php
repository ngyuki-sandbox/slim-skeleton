<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Http\Factory\DecoratedResponseFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([

        LoggerInterface::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $loggerSettings = $settings['logger'];

            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        ResponseFactoryInterface::class => function () {
            return new DecoratedResponseFactory(new ResponseFactory(), new StreamFactory());
        },

        Twig::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $options = [
                'debug' => $settings['debug'],
                'strict_variables' => true,
                'cache' => $settings['debug'] ? false : $settings['cacheDir'] . '/twig',
            ];
            return Twig::create(__DIR__ . '/../templates', $options);
        },

        Guard::class => function (App $app) {
            return (new Guard($app->getResponseFactory()))->setPersistentTokenMode(true);
        },
    ]);
};

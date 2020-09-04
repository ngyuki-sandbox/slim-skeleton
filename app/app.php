<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\App;

return function (bool $compile = false) {

    $containerBuilder = new ContainerBuilder();

    $cacheDir = __DIR__ . '/../var/cache';
    $containerCacheFile = "$cacheDir/CompiledContainer.php";

    if ($compile) {
        if (file_exists($containerCacheFile)) {
            unlink($containerCacheFile);
        }
        $containerBuilder->enableCompilation($cacheDir);
    } else {
        if (file_exists($containerCacheFile)) {
            $containerBuilder->enableCompilation($cacheDir);
        }
    }

    $settings = require __DIR__ . '/settings.php';
    $settings($containerBuilder);

    $dependencies = require __DIR__ . '/dependencies.php';
    $dependencies($containerBuilder);

    $repositories = require __DIR__ . '/repositories.php';
    $repositories($containerBuilder);

    $container = $containerBuilder->build();

    $app = $container->get(App::class);

    $routeCacheFile = "$cacheDir/routes.php";

    $middleware = require __DIR__ . '/middleware.php';
    $middleware($app);

    $routes = require __DIR__ . '/routes.php';
    $routes($app);

    $app->addRoutingMiddleware();

    if ($compile) {
        if (file_exists($routeCacheFile)) {
            unlink($routeCacheFile);
        }
        $app->getRouteCollector()->setCacheFile($routeCacheFile);
        $app->getRouteResolver()->computeRoutingResults('/', 'GET');
    } else {
        if (file_exists($routeCacheFile)) {
            $app->getRouteCollector()->setCacheFile($routeCacheFile);
        }
    }

    return $app;
};

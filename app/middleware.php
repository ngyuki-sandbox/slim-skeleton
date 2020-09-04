<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->add(Guard::class);
    $app->add(SessionMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
};

<?php
declare(strict_types=1);

use App\Application\Actions\HomeAction;
use App\Application\Actions\HomePostAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Slim\App;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (ServerRequest $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->map(['GET', 'POST'], '/', function (ServerRequest $request, Response $response) {
        if ($request->isPost()) {
            return $response->withRedirect('http://example.com/', 303);
        }
        return $response->withJson(['hello' => 'world!']);
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->get('/home', HomeAction::class)->setName('home');
    $app->post('/home', HomePostAction::class)->setName('home.post');
};

<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

class HomePostAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $location = RouteContext::fromRequest($request)->getRouteParser()->fullUrlFor($request->getUri(), 'home');
        return $response->withStatus(303)->withHeader('location', $location);
    }
}

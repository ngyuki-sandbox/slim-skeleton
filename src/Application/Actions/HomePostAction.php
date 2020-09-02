<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

class HomePostAction
{
    private Messages $flash;

    public function __construct(Messages $flash)
    {
        $this->flash = $flash;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->flash->addMessage('messages', 'this is flash message');

        $location = RouteContext::fromRequest($request)->getRouteParser()->fullUrlFor($request->getUri(), 'home');
        return $response->withStatus(303)->withHeader('location', $location);
    }
}

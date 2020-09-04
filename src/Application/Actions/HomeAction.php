<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Csrf\Guard;
use Slim\Views\Twig;

class HomeAction
{
    private Twig $twig;
    private Guard $csrf;

    public function __construct(Twig $twig, Guard $csrf)
    {
        $this->twig = $twig;
        $this->csrf = $csrf;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'home.twig', [
            'csrf'   => [
                'keys' => [
                    'name'  => $this->csrf->getTokenNameKey(),
                    'value' => $this->csrf->getTokenValueKey(),
                ],
                'name'  => $request->getAttribute($this->csrf->getTokenNameKey()),
                'value' => $request->getAttribute($this->csrf->getTokenValueKey()),
            ]
        ]);
    }
}

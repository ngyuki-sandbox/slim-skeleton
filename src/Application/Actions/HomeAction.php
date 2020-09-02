<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Csrf\Guard;
use Slim\Flash\Messages;
use Slim\Views\Twig;

class HomeAction
{
    private Twig $twig;
    private Messages $flash;
    private Guard $csrf;

    public function __construct(Twig $twig, Messages $flash, Guard $csrf)
    {
        $this->twig = $twig;
        $this->flash = $flash;
        $this->csrf = $csrf;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'home.twig', [
            'messages' => $this->flash->getMessage('messages'),
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

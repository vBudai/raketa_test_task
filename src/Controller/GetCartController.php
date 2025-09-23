<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\View\CartView;

readonly class GetCartController
{
    public function __construct(
        private CartView $cartView,
        private CartManager $cartManager
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
        $cart = $this->cartManager->getCart();

        $response->getBody()->write(
            json_encode(
                $this->cartView->toArray($cart),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(404);
    }
}

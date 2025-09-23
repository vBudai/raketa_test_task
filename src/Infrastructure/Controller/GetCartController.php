<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Infrastructure\Dto\Request\GetCartRequest;
use Raketa\BackendTestTask\Infrastructure\Dto\Response\JsonResponse;
use Raketa\BackendTestTask\Infrastructure\Repository\CartManager;
use Raketa\BackendTestTask\Infrastructure\View\CartView;

readonly class GetCartController
{
    public function __construct(
        private CartView $cartView,
        private CartManager $cartManager
    ) {
    }

    public function get(GetCartRequest $request): ResponseInterface
    {
        $response = new JsonResponse();
        $cart = $this->cartManager->getCart($request->customer);

        $response->getBody()->write(
            json_encode(
                $this->cartView->toArray($cart),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}

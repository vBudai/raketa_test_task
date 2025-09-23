<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Raketa\BackendTestTask\Application\AddToCartUseCase;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private AddToCartUseCase $useCase,
        private CartView $cartView,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);

        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                $this->cartView->toArray($this->useCase->add($rawRequest),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}

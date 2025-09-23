<?php

namespace Raketa\BackendTestTask\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\AddToCartUseCase;
use Raketa\BackendTestTask\Infrastructure\Dto\Request\AddToCartRequest;
use Raketa\BackendTestTask\Infrastructure\Dto\Response\JsonResponse;
use Raketa\BackendTestTask\Infrastructure\View\CartView;

readonly class AddToCartController
{
    public function __construct(
        private AddToCartUseCase $useCase,
        private CartView $cartView,
        private LoggerInterface $logger
    ) {
    }

    public function get(AddToCartRequest $request): ResponseInterface
    {
        $response = new JsonResponse();
        try {
            $response->getBody()->write(
                json_encode(
                    $this->cartView->toArray($this->useCase->add($request)),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
            $code = 201;
        } catch (\Doctrine\DBAL\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Error due running use case. Check logs for more information.',
            ]));
            $this->logger->error($e);
            $code = $e->getCode() !== 0 ? $e->getCode() : 500;
        }

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($code);
    }
}

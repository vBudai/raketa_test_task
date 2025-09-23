<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Application\GetProductsUseCase;
use Raketa\BackendTestTask\Infrastructure\Dto\Request\GetProductsRequest;
use Raketa\BackendTestTask\Infrastructure\Dto\Response\JsonResponse;
use Raketa\BackendTestTask\Infrastructure\View\ProductsView;


readonly class GetProductsController
{
    public function __construct(
        private GetProductsUseCase $useCase,
        private ProductsView $productsVew,
        private LoggerInterface $logger
    ) {
    }

    public function get(GetProductsRequest $request): ResponseInterface
    {
        $response = new JsonResponse();

        try{
            $products = $this->useCase->get($request);
            $response->getBody()->write(
                json_encode(
                    $this->productsVew->toArray($products),
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
            $code = 200;
        } catch (\Exception | \Doctrine\DBAL\Exception $e) {
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

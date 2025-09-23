<?php

namespace Raketa\BackendTestTask\Application;

use Doctrine\DBAL\Exception;
use Raketa\BackendTestTask\Domain\Product;
use Raketa\BackendTestTask\Infrastructure\Dto\Request\GetProductsRequest;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;

readonly class GetProductsUseCase
{
    public function __construct(
        private ProductRepository $productRepository,
    ){}

    /**
     * @return Product[]
     * @throws Exception
     */
    public function get(GetProductsRequest $request): array
    {
        return $this->productRepository->getActiveByCategory($request->category);
    }
}
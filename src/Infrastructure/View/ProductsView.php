<?php

namespace Raketa\BackendTestTask\Infrastructure\View;

use Raketa\BackendTestTask\Domain\Product;

readonly class ProductsView
{
    /**
     * @param Product[] $products
     */
    public function toArray(array $products): array
    {
        return array_map(
            static fn (Product $product) => [
                'id' => $product->getId(),
                'uuid' => $product->getUuid(),
                'category' => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail' => $product->getThumbnail(),
                'price' => $product->getPrice(),
            ],
            $products
        );
    }
}

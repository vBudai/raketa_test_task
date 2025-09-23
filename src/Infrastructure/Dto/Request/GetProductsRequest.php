<?php

namespace Raketa\BackendTestTask\Infrastructure\Dto\Request;

readonly class GetProductsRequest
{
    public function __construct(
        public string $category
    ){}
}
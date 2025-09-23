<?php

namespace Raketa\BackendTestTask\Infrastructure\Dto\Request;

use Raketa\BackendTestTask\Domain\Customer;

readonly class AddToCartRequest
{
    public function __construct(
        public string $productUuid,
        public int $quantity,
        public Customer $customer
    ){}
}
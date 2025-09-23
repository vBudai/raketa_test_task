<?php

namespace Raketa\BackendTestTask\Infrastructure\Dto\Request;

use Raketa\BackendTestTask\Domain\Customer;

readonly class GetCartRequest
{
    public function __construct(
        public Customer $customer
    ){}
}
<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Infrastructure\Db\RedisConnector;
use Raketa\BackendTestTask\Infrastructure\Db\RedisConnectorBuilder;
use Raketa\BackendTestTask\Infrastructure\Db\Exception\RedisConnectorException;
use Raketa\BackendTestTask\Repository\NotAuthorizedException;
use Ramsey\Uuid\Rfc4122\UuidV4;

class CartManager
{
    private RedisConnector $connector;

    /**
     * @throws RedisConnectorException
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        RedisConnectorBuilder $builder
    ){
        $this->connector = $builder->build();
    }

    public function saveCart(Cart $cart): void
    {
        try {
            $this->connector->set((string)$cart->getCustomer()->getId(), $cart);
        } catch (Exception $e) {
            $this->logger->error('Error: ' . $e->getMessage());
        }
    }

    public function getCart(Customer $customer): Cart
    {
        try {
            $cart = $this->connector->get((string)$customer->getId());
            return $cart ?? $this->createNewCart($customer);
        } catch (Exception $e) {
            $this->logger->error('Error: ' . $e->getMessage());
        }
    }

    private function createNewCart(Customer $customer): Cart
    {
        return new Cart(
            Uuidv4::uuid4()->toString(),
            $customer,
            'not selected',
            [],
        );
    }
}

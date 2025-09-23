<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Cassandra\Uuid;
use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Connector;
use Raketa\BackendTestTask\Infrastructure\ConnectorBuilder;
use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;

class CartManager
{
    private LoggerInterface $logger;

    private Connector $connector;

    /**
     * @throws ConnectorException
     */
    public function __construct(LoggerInterface $logger, ConnectorBuilder $builder)
    {
        $this->logger = $logger;
        $this->connector = $builder->build();
    }

    public function saveCart(Cart $cart)
    {
        $this->checkSession();

        try {
            $this->connector->set(session_id(), $cart);
        } catch (Exception $e) {
            $this->logger->error('Error');
        }
    }

    public function getCart(): Cart
    {
        $this->checkSession();

        try {
            $cart = $this->connector->get(session_id());
            return $cart ?? $this->createNewCart();
        } catch (Exception $e) {
            $this->logger->error('Error');
        }
    }

    private function createNewCart(): Cart
    {
        return new Cart(
            ...
        );
    }

    /**
     * @throws NotAuthorizedException
     */
    private function checkSession(): void
    {
        if(!session_id()){
            throw new NotAuthorizedException();
        }
    }
}

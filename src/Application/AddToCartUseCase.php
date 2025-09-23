<?php

namespace Raketa\BackendTestTask\Application;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;

class AddToCartUseCase
{
    public function __construct(
        private CartManager $cartManager,
        private ProductRepository $productRepository,
    ){}

    public function add(string $rawRequest): Cart
    {
        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        $cart = $this->cartManager->getCart();
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        $this->cartManager->saveCart($cart);
        return $cart;
    }
}
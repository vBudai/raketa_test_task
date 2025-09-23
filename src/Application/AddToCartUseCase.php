<?php

namespace Raketa\BackendTestTask\Application;

use Doctrine\DBAL\Exception;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Infrastructure\Dto\Request\AddToCartRequest;
use Raketa\BackendTestTask\Infrastructure\Repository\CartManager;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

readonly class AddToCartUseCase
{
    public function __construct(
        private CartManager $cartManager,
        private ProductRepository $productRepository,
    ){}

    /**
     * @throws Exception
     */
    public function add(AddToCartRequest $request): Cart
    {
        $product = $this->productRepository->getByUuid($request->productUuid);

        $cart = $this->cartManager->getCart($request->customer);
        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product,
            $request->quantity,
        ));

        $this->cartManager->saveCart($cart);
        return $cart;
    }
}
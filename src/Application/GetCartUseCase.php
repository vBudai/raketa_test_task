<?php

namespace Raketa\BackendTestTask\Application;

use Doctrine\DBAL\Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Dto\Request\GetCartRequest;
use Raketa\BackendTestTask\Infrastructure\Repository\CartManager;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;

readonly class GetCartUseCase
{
    public function __construct(
        private CartManager $cartManager,
        private ProductRepository $productRepository,
        private LoggerInterface $logger
    ){}

    /**
     * @throws Exception
     */
    public function get(GetCartRequest $request): Cart
    {
        $cart = $this->cartManager->getCart($request->customer);
        if($cart->getItems()){
            $this->updatePriceInCartItems($cart);
        }

        return $this->cartManager->getCart($request->customer);
    }

    private function updatePriceInCartItems(Cart $cart): void
    {
        foreach($cart->getItems() as $item){
            try{
                $product = $this->productRepository->getByUuid($item->getProduct()->getUuid());
                $item->setProduct($product);
            } catch (\Doctrine\DBAL\Exception | \Exception $e){
                $this->logger->warning('Error: ' . $e->getMessage());
            }
        }
    }
}